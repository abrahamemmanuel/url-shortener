<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Abis URL Shortener</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f2f2f2; /* Grey background */
        }
        header {
            background-color: black; /* Lavender */
            color: #fff;
            padding: 20px;
            display: flex; /* Use flexbox for layout */
            align-items: center; /* Center vertically */
        }
        header img {
            width: 100px; /* Adjust the width of the logo */
            height: auto; /* Maintain aspect ratio */
            margin-right: 20px; /* Add some space to the right */
        }
        main {
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: calc(100vh - 120px); /* Height minus header and footer */
        }
        section {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 500px;
            width: 100%;
        }
        label {
            font-weight: bold;
        }
        input[type="text"] {
            width: 95%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            padding: 10px 20px;
            background-color: black; /* Lavender */
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: black; /* Darker lavender on hover */
        }
        #short-url {
            width: calc(100% - 70px); /* Width minus the width of the copy button */
        }
        footer {
            background-color: black; /* Lavender */
            color: #fff;
            padding: 20px;
            text-align: center;
            border-radius: 0 0 8px 8px;
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
        }
        /* Toast style */
        .toast {
            visibility: hidden;
            min-width: 250px;
            margin: auto;
            background-color: #333;
            color: #fff;
            text-align: center;
            border-radius: 2px;
            padding: 16px;
            position: fixed;
            z-index: 1;
            left: 50%;
            transform: translateX(-50%);
            bottom: 30px;
        }
        /* Show the toast */
        .show {
            visibility: visible;
            animation: fadein 0.5s, fadeout 0.5s 4s;
        }
        /* Animations */
        @keyframes fadein {
            from {bottom: 0; opacity: 0;}
            to {bottom: 30px; opacity: 1;}
        }
        @keyframes fadeout {
            from {bottom: 30px; opacity: 1;}
            to {bottom: 0; opacity: 0;}
        }
    </style>
</head>
<body>
    <header>
        <img src="https://res.cloudinary.com/dqjwsobvg/image/upload/v1708533115/Screenshot_2024-02-21_at_16.49.01-removebg-preview.png" alt="">
        <div>
            <h1>Streamline Your Links, Expand Your Reach!</h1>
        </div>
    </header>
    <main>
        <section>
            <label for="long-url">Paste Your Long URL:</label><br>
            <input type="text" id="long-url" name="long-url" placeholder="Enter your long URL here"><br><br>
            <button onclick="generateShortUrl()">Generate Short URL</button><br><br>
            <p>Short URL:</p>
            <input type="text" id="short-url" readonly><button onclick="copyToClipboard()">Copy</button>
            <p><small><bold>NB:</bold> short url expires in 24 hours</small></p>
        </section>
    </main>
    <footer>
        © <script>document.write(new Date().getFullYear())</script>, made with ❤️ by Abis
    </footer>

    <div class="toast" id="toast"></div>

    <script>
        // Array of random quotes
        const quotes = [
            "The only way to do great work is to love what you do. - Steve Jobs",
            "Innovation distinguishes between a leader and a follower. - Steve Jobs",
            "The only limit to our realization of tomorrow will be our doubts of today. - Franklin D. Roosevelt",
            "The greatest glory in living lies not in never falling, but in rising every time we fall. - Nelson Mandela",
            "Your time is limited, don't waste it living someone else's life. - Steve Jobs",
            "It does not matter how slowly you go as long as you do not stop. - Confucius",
            "Success is not final, failure is not fatal: It is the courage to continue that counts. - Winston Churchill",
            "Life is what happens when you're busy making other plans. - John Lennon",
            "The future belongs to those who believe in the beauty of their dreams. - Eleanor Roosevelt",
            "Every strike brings me closer to the next home run. - Babe Ruth",
            "It is never too late to be what you might have been. - George Eliot",
            "Life is either a daring adventure or nothing at all. - Helen Keller",
            "Whatever you are, be a good one. - Abraham Lincoln",
            "Stay hungry, stay foolish. - Steve Jobs",
            "The only impossible journey is the one you never begin. - Tony Robbins",
            "Believe you can and you're halfway there. - Theodore Roosevelt",
            "Don't count the days, make the days count. - Muhammad Ali",
            "Life is trying things to see if they work. - Ray Bradbury",
            "Life is made of ever so many partings welded together. - Charles Dickens",
            "Don't watch the clock; do what it does. Keep going. - Sam Levenson",
            "Life is a journey, not a destination. - Ralph Waldo Emerson",
            "You only live once, but if you do it right, once is enough. - Mae West",
            "In the end, it's not the years in your life that count. It's the life in your years. - Abraham Lincoln",
            "It always seems impossible until it's done. - Nelson Mandela",
            "Life is like riding a bicycle. To keep your balance, you must keep moving. - Albert Einstein",
            "You miss 100% of the shots you don't take. - Wayne Gretzky",
            "The only way to do great work is to love what you do. - Steve Jobs",
            "Innovation distinguishes between a leader and a follower. - Steve Jobs",
            "The only limit to our realization of tomorrow will be our doubts of today. - Franklin D. Roosevelt",
            "The greatest glory in living lies not in never falling, but in rising every time we fall. - Nelson Mandela",
            "Your time is limited, don't waste it living someone else's life. - Steve Jobs",
            "It does not matter how slowly you go as long as you do not stop. - Confucius",
            "Success is not final, failure is not fatal: It is the courage to continue that counts. - Winston Churchill",
            "Life is what happens when you're busy making other plans. - John Lennon",
            "The future belongs to those who believe in the beauty of their dreams. - Eleanor Roosevelt",
            "Every strike brings me closer to the next home run. - Babe Ruth",
            "It is never too late to be what you might have been. - George Eliot",
            "Life is either a daring adventure or nothing at all. - Helen Keller",
            "Whatever you are, be a good one. - Abraham Lincoln",
            "Stay hungry, stay foolish. - Steve Jobs",
            "The only impossible journey is the one you never begin. - Tony Robbins",
            "Believe you can and you're halfway there. - Theodore Roosevelt",
            "Don't count the days, make the days count. - Muhammad Ali",
            "Life is trying things to see if they work. - Ray Bradbury",
            "Keep Shining 🌟! Keep Smiling 😊!",
            "With Love 💌 from Abis!",
            "Joy 😊 is Coming!",
            "Life is made of ever so many partings welded together. - Charles Dickens",
            "Don't watch the clock; do what it does. Keep going. - Sam Levenson",
            "Life is a journey, not a destination. - Ralph Waldo Emerson",
            "You only live once, but if you do it right, once is enough. - Mae West",
            "In the end, it's not the years in your life that count. It's the life in your years. - Abraham Lincoln",
            "It always seems impossible until it's done. - Nelson Mandela",
            "Life is like riding a bicycle. To keep your balance, you must keep moving. - Albert Einstein",
            "You miss 100% of the shots you don't take. - Wayne Gretzky",
            "Storm is Over! You should see the Sun 🌞 now!",
            "No Cap! You are a Star ⭐!",
            "Gold Star for You! 🌟",
            "You are a Winner! 🏆",
            "You are a Champion! 🥇",
            "Keep Streamlining your Links with Abis 👏!",
        ];

        function generateShortUrl() {
            const longUrlInput = document.getElementById("long-url");
            const longUrl = longUrlInput.value.trim();

            // Validate if long URL input is empty
            if (longUrl === "") {
                showToast("Long URL cannot be empty");
                return;
            }

            // Validate if long URL is a valid URL
            if (!isValidUrl(longUrl)) {
                showToast("Please enter a valid URL");
                return;
            }

            fetch('/encode', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ url: longUrl }),
            }).then(response =>
                response.json()
            ).then(data => {
                console.log('Success:', data.data.short_url);
                document.getElementById("short-url").value = data.data.short_url;
            }).catch((error) => {   
                console.error('Error:', error);
            });

            // Get a random quote
            const randomQuote = quotes[Math.floor(Math.random() * quotes.length)];
            showToast(randomQuote);
        }

        function showToast(message) {
            var toast = document.getElementById("toast");
            toast.innerHTML = message;
            toast.className = "toast show";
            setTimeout(function(){ toast.className = toast.className.replace("show", ""); }, 5000);
        }

        function copyToClipboard() {
            if (document.getElementById("short-url").value === "") {
                return;
            }
            var shortUrlInput = document.getElementById("short-url");
            shortUrlInput.select();
            document.execCommand("copy");
            showToast("Short URL copied to clipboard!");
        }

        function isValidUrl(url) {
            // Regular expression for validating URL
            const urlRegex = /^(ftp|http|https):\/\/[^ "]+$/;
            return urlRegex.test(url);
        }
    </script>
</body>
</html>
