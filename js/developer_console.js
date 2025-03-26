/*(function() {
    function checkConsole() {
        let start = new Date();
        debugger; // Triggers a breakpoint (detects DevTools)
        let end = new Date();

        if (end - start > 100) { // If time delay is high, DevTools is open
            console.warn('%cStop!', 'color: red; font-size: 50px; font-weight: bold;');
            console.warn(
                '%cThis is a browser feature intended for developers. If someone told you to copy-paste something here to enable a CCDI Online Payment feature or "hack" someone\'s account, it is a scam and will give them access to your CCDI Online Payment account.',
                'color: black; font-size: 16px;'
            );
            console.warn(
                'See https://www.facebook.com/selfxss for more information.'
            );
        }
    }

    // Run detection every second
    setInterval(checkConsole, 1000);
})();
*/