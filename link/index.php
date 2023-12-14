<script>
function getOS() {
        
        var OS = "Unknown";
        var _isMobile = navigator.userAgent.toLowerCase().match(/mobile/i);
        var isMobile = "PC";
        
        if (navigator.appVersion.indexOf("Win")!=-1) OS="Windows";
            else if (navigator.appVersion.indexOf("Mac")!=-1) OS="MacOS";
                else if (navigator.appVersion.indexOf("X11")!=-1) OS="UNIX";
                    else if (navigator.appVersion.indexOf("Linux")!=-1) OS="Linux";

    if (_isMobile == "mobile") {
        
        window.location.replace("https://www.plata.ie/en/mobile/");
    } else
    {
        window.location.replace("https://www.plata.ie/en/");
    }
    
    //isMobile = "mobile";

    //console.log(navigator.userAgent);

        let userAgent = navigator.userAgent;
        let browserName;
         
        if(userAgent.match(/chrome|chromium|crios/i)){
            browserName = "Chrome";
        } else if(userAgent.match(/firefox|fxios/i)){
                    browserName = "Firefox";
                } else if(userAgent.match(/safari/i)){
                            browserName = "Safari";
                        }else if(userAgent.match(/opr\//i)){
                                    browserName = "Opera";
                                } else if(userAgent.match(/edg/i)){
                                            browserName = "Edge";
                                        } else { browserName="Unknown Browser";
           }

    console.log("Operating System: " + OS + " " + isMobile + " " + browserName);

    } getOS();
    
</script>