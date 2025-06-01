(function($) {
    'use strict';

    var K3EMigrateExporter = {
        currentStep: 1,
        selectedCPT: '',
        selectedMetaFields: [],
        
        init: function() {
            this.loadCPTList();
            this.initEvents();
        },
        
        initEvents: function() {
            var self = this;
            
            $('#export-cpt').on('change', function() {
                var selectedCPT = $(this).val();
                
                if (selectedCPT) {
                    self.selectedCPT = selectedCPT;
                    self.loadMetaFields(selectedCPT);
                    $('#go-to-step-2').prop('disabled', false);
                } else {
                    $('#go-to-step-2').prop('disabled', true);
                }
            });
            
            $('#go-to-step-2').on('click', function() {
                self.goToStep(2);
            });
            
            $('#back-to-step-1').on('click', function() {
                self.goToStep(1);
            });
            
            $('#start-export').on('click', function() {
                if (confirm(k3e_migrate_vars.strings.confirm_export)) {
                    self.startExport();
                }
            });
            
            $('#new-export').on('click', function() {
                window.location.reload();
            });
            
            $(document).on('change', '.meta-field-checkbox', function() {
                self.updateSelectedMetaFields();
            });
            
            $(document).on('click', '#select-all-meta', function() {
                $('.meta-field-checkbox').prop('checked', true);
                self.updateSelectedMetaFields();
            });
            
            $(document).on('click', '#deselect-all-meta', function() {
                $('.meta-field-checkbox').prop('checked', false);
                self.updateSelectedMetaFields();
            });
        },
        
        loadCPTList: function() {
            var self = this;
            
            $('.k3e-migrate-loading').show();
            
            $.ajax({
                url: k3e_migrate_vars.ajaxurl,
                type: 'POST',
                data: {
                    action: 'k3e_migrate_get_cpt_list',
                    nonce: k3e_migrate_vars.nonce
                },
                success: function(response) {
                    if (response.success) {
                        var cptList = response.data;
                        var options = '<option value="">' + 'Wybierz Custom Post Type' + '</option>';
                        
                        $.each(cptList, function(index, cpt) {
                            options += '<option value="' + cpt.name + '">' + cpt.label + ' (' + cpt.name + ') - ' + cpt.count + ' postów</option>';
                        });
                        
                        $('#export-cpt').html(options);
                    } else {
                        alert(response.data);
                    }
                },
                error: function() {
                    alert('Wystąpił błąd podczas ładowania typów treści.');
                },
                complete: function() {
                    $('.k3e-migrate-loading').hide();
                }
            });
        },
        
        loadMetaFields: function(postType) {
            var self = this;
            
            $('.k3e-migrate-loading').show();
            
            $.ajax({
                url: k3e_migrate_vars.ajaxurl,
                type: 'POST',
                data: {
                    action: 'k3e_migrate_get_meta_fields',
                    nonce: k3e_migrate_vars.nonce,
                    post_type: postType
                },
                success: function(response) {
                    if (response.success) {
                        var data = response.data;
                        $('#posts-count').text(data.posts_count);
                        self.generateMetaFieldsList(data.meta_fields);
                    } else {
                        alert(response.data);
                    }
                },
                error: function() {
                    alert('Wystąpił błąd podczas ładowania pól meta.');
                },
                complete: function() {
                    $('.k3e-migrate-loading').hide();
                }
            });
        },
        
        generateMetaFieldsList: function(metaFields) {
            var html = '';
            
            if (metaFields.length === 0) {
                html = '<p>Nie znaleziono pól meta dla wybranego typu treści.</p>';
            } else {
                html += '<div class="meta-fields-actions">';
                html += '<button type="button" id="select-all-meta" class="button">Zaznacz wszystkie</button> ';
                html += '<button type="button" id="deselect-all-meta" class="button">Odznacz wszystkie</button>';
                html += '</div>';
                
                html += '<div class="meta-fields-grid">';
                
                $.each(metaFields, function(index, field) {
                    html += '<div class="meta-field-item">';
                    html += '<label>';
                    html += '<input type="checkbox" class="meta-field-checkbox" value="' + field.key + '" checked> ';
                    html += '<strong>' + field.key + '</strong>';
                    html += '<span class="meta-field-count">(' + field.count + ' użyć)</span>';
                    if (field.sample_value) {
                        html += '<div class="meta-field-sample">Przykład: ' + field.sample_value + '</div>';
                    }
                    html += '</label>';
                    html += '</div>';
                });
                
                html += '</div>';
            }
            
            $('#meta-fields-list').html(html);
            this.updateSelectedMetaFields();
        },
        
        updateSelectedMetaFields: function() {
            this.selectedMetaFields = [];
            $('.meta-field-checkbox:checked').each(function() {
                this.selectedMetaFields.push($(this).val());
            }.bind(this));
        },
        
        goToStep: function(step) {
            $('.k3e-migrate-step').removeClass('active');
            $('#step-' + step).addClass('active');
            this.currentStep = step;
        },
        
        startExport: function() {
            var self = this;
            
            self.goToStep(3);
            
            var options = {
                export_titles: $('#export-titles').is(':checked'),
                export_content: $('#export-content').is(':checked'),
                export_thumbnails: $('#export-thumbnails').is(':checked')
            };
            
            $.ajax({
                url: k3e_migrate_vars.ajaxurl,
                type: 'POST',
                data: {
                    action: 'k3e_migrate_start_export',
                    nonce: k3e_migrate_vars.nonce,
                    post_type: self.selectedCPT,
                    options: options,
                    meta_fields: self.selectedMetaFields
                },
                success: function(response) {
                    if (response.success) {
                        self.processExportBatch();
                    } else {
                        self.showError(response.data);
                    }
                },
                error: function() {
                    self.showError('Wystąpił błąd podczas inicjalizacji eksportu.');
                }
            });
        },
        
        processExportBatch: function() {
            var self = this;
            
            $.ajax({
                url: k3e_migrate_vars.ajaxurl,
                type: 'POST',
                data: {
                    action: 'k3e_migrate_process_export_batch',
                    nonce: k3e_migrate_vars.nonce
                },
                success: function(response) {
                    if (response.success) {
                        self.updateProgressBar(response.data);
                        
                        $('.k3e-migrate-progress-status').text(
                            k3e_migrate_vars.strings.processing + response.data.processed + ' z ' + response.data.total
                        );
                        
                        if (response.data.completed) {
                            self.finishExport(response.data);
                        } else {
                            setTimeout(function() {
                                self.processExportBatch();
                            }, 500);
                        }
                    } else {
                        self.showError(response.data);
                    }
                },
                error: function() {
                    self.showError('Wystąpił błąd podczas przetwarzania eksportu.');
                }
            });
        },
        
        updateProgressBar: function(data) {
            var progress = 0;
            
            if (data.total > 0) {
                progress = Math.round((data.processed / data.total) * 100);
            }
            
            $('.k3e-migrate-progress-bar-inner').css('width', progress + '%');
            $('.k3e-migrate-progress-percentage').text(progress + '%');
        },
        
        finishExport: function(data) {
            $('.k3e-migrate-progress-status').text(k3e_migrate_vars.strings.export_complete);
            
            $('.export-summary').html(
                'Pomyślnie wyeksportowano <strong>' + data.processed + '</strong> elementów w czasie <strong>' + this.formatTime(data.time_elapsed) + '</strong>.'
            );
            
            $('#download-export').attr('href', data.download_url);
            
            $('.k3e-migrate-export-results').show();
        },
        
        showError: function(message) {
            $('.k3e-migrate-progress-status').html('<span style="color: red;">' + k3e_migrate_vars.strings.error + message + '</span>');
        },
        
        formatTime: function(seconds) {
            seconds = Math.round(seconds);
            var minutes = Math.floor(seconds / 60);
            var remainingSeconds = seconds % 60;
            
            if (minutes > 0) {
                return minutes + ' min ' + remainingSeconds + ' s';
            } else {
                return seconds + ' s';
            }
        }
    };

    var K3EMigrateImporter = {
        importData: null,
        
        init: function() {
            this.initEvents();
        },
        
        initEvents: function() {
            var self = this;
            
            $('#import-file').on('change', function() {
                self.handleFileSelect(this);
            });
            
            $('#start-import').on('click', function() {
                if (confirm(k3e_migrate_vars.strings.confirm_import)) {
                    self.startImport();
                }
            });
            
            $('#new-import').on('click', function() {
                window.location.reload();
            });
        },
        
        handleFileSelect: function(input) {
            var self = this;
            
            if (input.files && input.files[0]) {
                var file = input.files[0];
                
                if (file.type !== 'application/json' && !file.name.toLowerCase().endsWith('.json')) {
                    alert('Proszę wybrać plik JSON.');
                    return;
                }
                
                var reader = new FileReader();
                reader.onload = function(e) {
                    try {
                        self.importData = JSON.parse(e.target.result);
                        self.validateImportData();
                    } catch (error) {
                        alert('Błąd podczas odczytywania pliku JSON: ' + error.message);
                        self.resetImportForm();
                    }
                };
                reader.readAsText(file);
            }
        },
        
        validateImportData: function() {
            if (!this.importData || !this.importData.items || !Array.isArray(this.importData.items)) {
                alert('Nieprawidłowy format pliku JSON.');
                this.resetImportForm();
                return;
            }
            
            if (!this.importData.export_info || !this.importData.export_info.post_type) {
                alert('Plik JSON nie zawiera informacji o typie treści.');
                this.resetImportForm();
                return;
            }
            
            this.showImportSummary();
            $('.import-options').show();
            $('#start-import').prop('disabled', false);
        },
        
        showImportSummary: function() {
            var summary = '';
            var exportInfo = this.importData.export_info;
            
            summary += '<p><strong>Typ treści:</strong> ' + exportInfo.post_type + '</p>';
            summary += '<p><strong>Data eksportu:</strong> ' + exportInfo.date + '</p>';
            summary += '<p><strong>Źródłowa strona:</strong> ' + exportInfo.site_url + '</p>';
            summary += '<p><strong>Liczba elementów:</strong> ' + this.importData.items.length + '</p>';
            
            if (exportInfo.meta_fields && exportInfo.meta_fields.length > 0) {
                summary += '<p><strong>Pola meta:</strong> ' + exportInfo.meta_fields.join(', ') + '</p>';
            }
            
            $('.import-summary').html(summary);
        },
        
        startImport: function() {
            var self = this;
            
            var cptMode = $('input[name="cpt-mode"]:checked').val();
            var importMode = $('input[name="import-mode"]:checked').val();
            
            $('.k3e-migrate-import-progress').show();
            $('#start-import').prop('disabled', true);
            
            $.ajax({
                url: k3e_migrate_vars.ajaxurl,
                type: 'POST',
                data: {
                    action: 'k3e_migrate_import_data',
                    nonce: k3e_migrate_vars.nonce,
                    json_data: JSON.stringify(self.importData),
                    cpt_mode: cptMode,
                    import_mode: importMode
                },
                success: function(response) {
                    if (response.success) {
                        self.processImportBatch();
                    } else {
                        self.showError(response.data);
                    }
                },
                error: function() {
                    self.showError('Wystąpił błąd podczas inicjalizacji importu.');
                }
            });
        },
        
        processImportBatch: function() {
            var self = this;
            
            $.ajax({
                url: k3e_migrate_vars.ajaxurl,
                type: 'POST',
                data: {
                    action: 'k3e_migrate_process_import_batch',
                    nonce: k3e_migrate_vars.nonce
                },
                success: function(response) {
                    if (response.success) {
                        self.updateProgressBar(response.data);
                        
                        $('.k3e-migrate-progress-status').text(
                            k3e_migrate_vars.strings.processing + response.data.processed + ' z ' + response.data.total
                        );
                        
                        if (response.data.completed) {
                            self.finishImport(response.data);
                        } else {
                            setTimeout(function() {
                                self.processImportBatch();
                            }, 500);
                        }
                    } else {
                        self.showError(response.data);
                    }
                },
                error: function() {
                    self.showError('Wystąpił błąd podczas przetwarzania importu.');
                }
            });
        },
        
        updateProgressBar: function(data) {
            var progress = 0;
            
            if (data.total > 0) {
                progress = Math.round((data.processed / data.total) * 100);
            }
            
            $('.k3e-migrate-progress-bar-inner').css('width', progress + '%');
            $('.k3e-migrate-progress-percentage').text(progress + '%');
        },
        
        finishImport: function(data) {
            $('.k3e-migrate-progress-status').text(k3e_migrate_vars.strings.import_complete);
            
            var summary = '<p><strong>Przetworzono:</strong> ' + data.processed + ' elementów</p>';
            summary += '<p><strong>Pomyślnie zaimportowano:</strong> ' + data.successful + ' elementów</p>';
            summary += '<p><strong>Zaktualizowano:</strong> ' + data.updated + ' elementów</p>';
            summary += '<p><strong>Utworzono nowych:</strong> ' + data.created + ' elementów</p>';
            summary += '<p><strong>Błędy:</strong> ' + data.failed + ' elementów</p>';
            summary += '<p><strong>Czas trwania:</strong> ' + this.formatTime(data.time_elapsed) + '</p>';
            
            $('.import-summary-final').html(summary);
            
            if (data.errors && data.errors.length > 0) {
                var errorsHTML = '';
                $.each(data.errors, function(index, error) {
                    errorsHTML += '<li>' + error + '</li>';
                });
                $('.import-error-list').html(errorsHTML);
                $('.import-errors').show();
            }
            
            $('.k3e-migrate-import-results').show();
            $('#start-import').prop('disabled', false);
        },
        
        showError: function(message) {
            $('.k3e-migrate-import-progress').hide();
            $('.k3e-migrate-progress-status').html('<span style="color: red;">' + k3e_migrate_vars.strings.error + message + '</span>');
            $('#start-import').prop('disabled', false);
        },
        
        resetImportForm: function() {
            this.importData = null;
            $('.import-options').hide();
            $('.import-summary').empty();
            $('#start-import').prop('disabled', true);
            $('#import-file').val('');
        },
        
        formatTime: function(seconds) {
            seconds = Math.round(seconds);
            var minutes = Math.floor(seconds / 60);
            var remainingSeconds = seconds % 60;
            
            if (minutes > 0) {
                return minutes + ' min ' + remainingSeconds + ' s';
            } else {
                return seconds + ' s';
            }
        }
    };
    
    $(document).ready(function() {
        var currentPage = new URLSearchParams(window.location.search).get('page');
        
        if (currentPage === 'k3e-migrate-export') {
            K3EMigrateExporter.init();
        } else if (currentPage === 'k3e-migrate-import') {
            K3EMigrateImporter.init();
        }
    });
    
})(jQuery);