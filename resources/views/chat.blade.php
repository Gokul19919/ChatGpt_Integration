<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ChatGPT</title>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            background-color: #343541;
            color: #d1d5db;
            display: flex;
            flex-direction: column;
            height: 100vh;
            overflow: hidden;
        }

        header {
            background-color: #202123;
            padding: 10px 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            border-bottom: 1px solid #3f3f46;
        }

        header svg {
            width: 40px;
            height: 40px;
        }

        header h1 {
            color: #ffffff;
            font-size: 1.5rem;
            font-weight: bold;
        }

        #chat-container {
            flex: 1;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        #chat-box {
            flex: 1;
            padding: 20px;
            overflow-y: auto;
            background-color: #444654;
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .message {
            display: flex;
            align-items: flex-start;
        }

        .message.user {
            justify-content: flex-end;
        }

        .message.bot {
            justify-content: flex-start;
        }

        .message-content {
            max-width: 75%;
            padding: 12px 15px;
            border-radius: 10px;
            line-height: 1.5;
            word-wrap: break-word;
            font-size: 1rem;
        }

        .user .message-content {
            background-color: #007bff;
            color: #ffffff;
            border-bottom-right-radius: 0;
        }

        .bot .message-content {
            background-color: #e5e7eb;
            color: #111827;
            border-bottom-left-radius: 0;
        }

        .bot-avatar {
            width: 40px;
            height: 40px;
            margin-right: 10px;
            border-radius: 50%;
            background: url('https://upload.wikimedia.org/wikipedia/commons/0/04/OpenAI_Logo.svg') no-repeat center center;
            background-size: contain;
        }

        #input-container {
            display: flex;
            align-items: center;
            padding: 15px;
            background-color: #202123;
            border-top: 1px solid #3f3f46;
            gap: 10px;
        }

        #user-input {
            flex: 1;
            padding: 12px;
            border: none;
            border-radius: 5px;
            background-color: #3f3f46;
            color: #ffffff;
            font-size: 1rem;
        }

        #user-input:focus {
            outline: none;
        }

        button {
            padding: 10px 20px;
            font-size: 1rem;
            background-color: #007bff;
            color: #ffffff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }

        @media (max-width: 768px) {
            header h1 {
                font-size: 1.2rem;
            }

            #user-input {
                font-size: 0.9rem;
                padding: 10px;
            }

            button {
                padding: 10px 15px;
                font-size: 0.9rem;
            }

            .message-content {
                font-size: 0.9rem;
            }
        }
    </style>
</head>
<body>
    <header>
        <svg xmlns="http://www.w3.org/2000/svg" width="256" height="256" fill="none" viewBox="0 0 256 256" id="openai">
  <rect width="224" height="224" x="16" y="16" fill="#EEE" rx="70"></rect>
  <path fill="#000" d="M185.149 115.895C186.639 111.405 187.155 106.649 186.661 101.944C186.167 97.2397 184.675 92.6946 182.284 88.6126C175.011 75.9531 160.394 69.4412 146.118 72.5012C142.165 68.1043 137.125 64.825 131.504 62.9924C125.883 61.1599 119.878 60.8386 114.094 62.061C108.309 63.2833 102.948 66.0062 98.5485 69.9562C94.1492 73.9062 90.8666 78.9442 89.0305 84.5642C84.3952 85.5147 80.0162 87.4436 76.1861 90.2221C72.356 93.0005 69.1631 96.5644 66.8207 100.676C59.4688 113.315 61.138 129.258 70.9477 140.102C69.4514 144.589 68.9305 149.344 69.4201 154.049C69.9096 158.754 71.3982 163.3 73.7863 167.383C81.0683 180.047 95.695 186.559 109.979 183.495C113.119 187.031 116.978 189.857 121.297 191.783C125.616 193.709 130.297 194.692 135.026 194.666C149.659 194.679 162.622 185.233 167.092 171.3C171.727 170.347 176.105 168.418 179.935 165.64C183.764 162.861 186.958 159.298 189.301 155.188C196.564 142.571 194.888 126.715 185.149 115.895ZM135.026 185.94C129.186 185.949 123.528 183.902 119.046 180.157L119.834 179.71L146.38 164.387C147.041 164 147.59 163.447 147.972 162.784C148.355 162.12 148.558 161.369 148.562 160.603V123.175L159.785 129.667C159.897 129.724 159.975 129.831 159.996 129.956V160.97C159.967 174.748 148.804 185.911 135.026 185.94ZM81.356 163.021C78.4269 157.963 77.3752 152.035 78.3859 146.278L79.174 146.751L105.747 162.074C106.404 162.46 107.153 162.664 107.915 162.664C108.677 162.664 109.426 162.46 110.083 162.074L142.543 143.361V156.318C142.54 156.385 142.522 156.451 142.49 156.51C142.458 156.569 142.414 156.621 142.359 156.66L115.472 172.167C103.523 179.051 88.2578 174.958 81.356 163.021ZM74.3642 105.197C77.3137 100.107 81.969 96.2241 87.5062 94.2367V125.776C87.4962 126.538 87.6909 127.289 88.07 127.95C88.4491 128.611 88.9987 129.159 89.6614 129.535L121.963 148.17L110.74 154.662C110.68 154.694 110.612 154.711 110.543 154.711C110.475 154.711 110.407 154.694 110.346 154.662L83.5112 139.182C71.5863 132.27 67.4968 117.016 74.3642 105.066V105.197ZM166.566 126.618L134.159 107.799L145.356 101.333C145.417 101.301 145.484 101.284 145.553 101.284C145.622 101.284 145.69 101.301 145.751 101.333L172.586 116.841C176.689 119.208 180.034 122.694 182.23 126.891C184.426 131.088 185.383 135.824 184.989 140.545C184.595 145.265 182.866 149.776 180.004 153.551C177.143 157.326 173.266 160.209 168.827 161.864V130.324C168.804 129.563 168.583 128.821 168.187 128.172C167.791 127.522 167.232 126.987 166.566 126.618ZM177.737 109.823L176.948 109.349L150.428 93.8951C149.767 93.507 149.014 93.3024 148.247 93.3024C147.48 93.3024 146.727 93.507 146.065 93.8951L113.631 112.609V99.6518C113.624 99.5859 113.635 99.5194 113.663 99.4592C113.691 99.3991 113.734 99.3476 113.789 99.3101L140.624 83.829C144.737 81.4597 149.439 80.3104 154.182 80.5155C158.924 80.7207 163.509 82.2717 167.402 84.9873C171.295 87.703 174.334 91.4709 176.164 95.8503C177.994 100.23 178.539 105.04 177.736 109.718V109.823H177.737ZM107.508 132.794L96.2848 126.329C96.2287 126.295 96.1807 126.249 96.1443 126.194C96.1079 126.14 96.084 126.078 96.0742 126.013V95.0783C96.0804 90.3326 97.4375 85.6868 99.9867 81.684C102.536 77.6812 106.172 74.4868 110.47 72.4744C114.768 70.4619 119.55 69.7146 124.257 70.3197C128.964 70.9248 133.401 72.8574 137.05 75.8914L136.261 76.3384L109.715 91.6613C109.055 92.0488 108.506 92.6015 108.124 93.2651C107.741 93.9286 107.538 94.6803 107.534 95.4461L107.508 132.795V132.794ZM113.605 119.652L128.061 111.321L142.543 119.653V136.316L128.114 144.648L113.632 136.316L113.605 119.652Z"></path>
</svg>
        <h1>ChatGPT</h1>
    </header>
    <div id="chat-container">
        <div id="chat-box">
        </div>
        <div id="input-container">
            <input type="text" id="user-input" placeholder="Type a message..." />
            <button onclick="sendMessage()">Send</button>
        </div>
    </div>

    <script>
        async function sendMessage() {
            const message = document.getElementById('user-input').value;

            if (!message.trim()) {
                alert('Please type a message.');
                return;
            }

            const chatBox = document.getElementById('chat-box');

            chatBox.innerHTML += `
                <div class="message user">
                    <div class="message-content">${message}</div>
                </div>
            `;
            chatBox.scrollTop = chatBox.scrollHeight;

            try {
                const response = await axios.post('/chat', { message });
                chatBox.innerHTML += `
                    <div class="message bot">
                        <div class="bot-avatar"></div>
                        <div class="message-content">${response.data.message}</div>
                    </div>
                `;
                chatBox.scrollTop = chatBox.scrollHeight;
            } catch (error) {
                console.error('Error sending message:', error);
                alert('An error occurred. Please try again.');
            }

            document.getElementById('user-input').value = '';
        }
    </script>
</body>
</html>
