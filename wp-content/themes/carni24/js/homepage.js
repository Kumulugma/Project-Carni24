window.addEventListener("scroll", () => {

//    setOpacity("news", checkpointStartNews, checkpointEndNews);
    setOpacity("carousel");
    setOpacity("feature");
    setOpacity("manifest");
    setOpacity("footer");
});

function setOpacity(selector)
{
    let element = document.getElementById(selector);
    let position = element.getBoundingClientRect();
    let checkpointStart = position.top * 1.2;
    let checkpointEnd = (position.top + (((window.screen.height * (element.offsetHeight * .3)) / 100)));
    console.log(window.screen.height);
    console.log(checkpointStart);
    console.log(checkpointEnd);
//    console.log("START: "+ selector + " = " + position.top);
//    console.log("END: " + selector + " = " + (position.top + element.offsetHeight));
    let currentScroll = window.pageYOffset;
    if (currentScroll <= checkpointEnd) {
        if (currentScroll <= checkpointStart) {
            opacity = .2;
        } else {
            opacity = .2 + (currentScroll - checkpointStart) / (checkpointEnd - checkpointStart);
        }
    } else {
        opacity = 1;
    }
    document.querySelector("#"+selector).style.opacity = opacity;
}