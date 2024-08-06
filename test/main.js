/**
 * Asynchronously sends a GET request to a url
 * @param {string} url the url to send the request to
 * @param {(XMLHttpRequest) => void} callback the function to be called when the request is completed
 */
function httpAsyncGet(url, callback) {
    let request = new XMLHttpRequest();
    request.onreadystatechange = () => {
        if (request.readyState == 4) {
            callback(request);
        }
    }
    request.open("GET", url, true);
    request.send();
}

const elements = Object.freeze({
    resultDiv: document.getElementById("results"),
    wpm: document.getElementById("wpm"),
    accuracy: document.getElementById("accuracy"),
    global: document.getElementById("global"),
    personal: document.getElementById("personal"),
    playAgain: document.getElementById("play-again"),
});

class Test {
    /**
     * @param {string} level the name of the level to use
     * @param {HTMLParagraphElement} textP the element to put the words in
     * @param {HTMLInputElement} input the element to take input from
     * @param {HTMLPreElement} result the element to put the test's results in
     */
    constructor(level, textP, input, result) {
        this.errors = 0;
        this.active = false;
        this.startTime = -1;
        this.wpm = -1;
        this.accuracy = -1;

        // get test text
        textP.innerText = "Fetching words. . .";
        if (level == null) {
            textP.innerText = "Invalid URL: please select a level first!";
            throw new Error("No level provided");
        }
        httpAsyncGet("../level/fetch.php?level=" + new String(level).toString(), (request) => {
            switch (request.status) {
                case 200: // success
                    textP.innerText = request.responseText;
                    this.words = request.responseText.split(/\s+/);
                    this.#start();
                    break;
                case 400: // invalid level provided
                case 404:
                    textP.innerText = "Requested level couldn't be found";
                    break;
                default:
                    textP.innerText = "Error: Got HTTP response code " + request.status;
                    break;
            }
        })
        this.text = textP;
        input.disabled = true;
        this.input = input;
        this.result = result;
    }

    handleKeyPress(event) {
        if (this.active && event.target == this.input) {
            if (event.code == "Enter" && this.input.value.split(" ").length >= this.words.length) {
                this.stop();
            } else if (this.input.value.length == 0) {
                console.log("Timer started", this.input.value);
                this.startTime = Date.now();
            }
        }
    }

    #start() {
        this.active = true;
        this.input.disabled = false;
        this.input.focus();
    }

    /**
     * Add a child span to this.text
     * @param {string} str the text within the span
     * @param {string} color the color of the str
     * @param {bool} underline if the str should be underlined
     */
    #addSpan(str, color, underline) {
        let span = document.createElement("span");
        span.style.color = color;
        if (underline) {
            span.style.textDecoration = "underline " + color;
        }
        span.appendChild(document.createTextNode(str));
        this.text.appendChild(span);
    }

    stop() {
        this.active = false;
        elements.playAgain.classList.remove("hidden");
        // grade input text
        this.wpm = Math.round(this.words.length / ((Date.now() - this.startTime) / 1000 / 60));
        let text = this.input.value.split(/\s+/);
        let correct = 0;
        let total = 0;
        this.words.forEach(e => total += e.length);
        
        // loop over each word and compare letters
        this.text.innerText = "";
        for (let i = 0; i < text.length; i++) {
            let element = text[i];
            if (i >= this.words.length) {
                break;
            }

            // compare original against input
            for (let j = 0; j < this.words[i].length; j++) {
                let matching = this.words[i][j] == element[j];
                this.#addSpan(
                    this.words[i][j],
                    matching? "black" : "red",
                    !matching
                );
                if (matching) correct++;
            }
            this.#addSpan(" ", "green");
        }

        // put results in the DOM
        elements.resultDiv.classList.remove("hidden");
        elements.wpm.innerText = `WPM: ${this.wpm}`;
        elements.accuracy.innerText = `Accuracy: ${Math.round(correct / total * 100)}%`
        this.input.disabled = true;
        // must be a callback or <Enter> keypress will both end and start a test
        setTimeout(() => elements.playAgain.focus(), 0);
    }
}

let test = new Test(new URLSearchParams(window.location.search).get('level'), 
    document.getElementById("text"), 
    document.getElementById("text-input")
);
document.body.onkeydown = (e) => test.handleKeyPress(e);
