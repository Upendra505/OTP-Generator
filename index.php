<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book a Free Session</title>
    <link rel="stylesheet" href="style.css">

    <style>
        /* Additional styles */
        .otp-inputs {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }

        .otp {
            width: 40px;
            height: 40px;
            font-size: 24px;
            text-align: center;
            border: 2px solid #ccc;
            border-radius: 4px;
            margin-right: 5px;
            transition: border-color 0.3s ease;
        }

        .otp.success {
            border-color: green;
            background-color: #e0f7e0;
        }

        .otp.error {
            border-color: red;
            background-color: #f7e0e0;
        }

        #timer {
            margin-left: 10px;
            font-weight: bold;
            color: red;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="form-container">
            <div class="logo">
                <!-- <img src="IMG_3746.JPG" alt="Starkin Academy" style="width: 60px;" />
                <h1>Starkin Academy</h1> -->
            </div>
            <h2 style="font-size: 35px;">Book a Free Session</h2>
            <form id="sessionForm" action="process_form.php" method="POST">
                <div class="input-group">
                    <label for="name">Name</label>
                    <input type="text" id="name" name="name" placeholder="Enter your name" required />
                </div>
                <div class="input-group">
                    <label for="phone">Mobile number (WhatsApp Number)</label>
                    <div class="phone-group">
                        <select id="country-code">
                            <option value="+91">IN +91</option>
                        </select>
                        <input type="tel" id="phone" name="phone" placeholder="Enter your phone number" required />
                    </div>
                    <button type="button" id="get-otp" onclick="sendOtp()">Get OTP</button>
                </div>

                <div class="input-group" id="otp-group" style="display: none;">
                    <label for="otp">Enter OTP</label>
                    <div class="otp-inputs">
                        <input type="text" maxlength="1" class="otp" id="otp1" />
                        <input type="text" maxlength="1" class="otp" id="otp2" />
                        <input type="text" maxlength="1" class="otp" id="otp3" />
                        <input type="text" maxlength="1" class="otp" id="otp4" />
                    </div>
                    <button type="button" id="go-back" onclick="goBack()" disabled>Edit phone</button>
                    <span id="timer">01:00</span>
                </div>

                <div class="input-group">
                    <label for="qualification">Highest Qualification</label>
                    <select id="qualification" name="qualification" required>
                        <option value="" disabled selected>---Select---</option>
                        <option value="High School">High School</option>
                        <option value="Graduate">Graduate</option>
                        <option value="Post Graduate">Post Graduate</option>
                    </select>
                </div>
                <div class="input-group">
                    <label for="course">Select Course</label>
                    <select id="course" name="course" required>
                        <option value="" disabled selected>---Select---</option>
                        <option value="Java Full Stack">Java Full Stack</option>
                        <option value="Python Full Stack">Python Full Stack</option>
                        <option value="Ui/Ux">Ui/Ux</option>
                        <option value="Digital Marketing">Digital Marketing</option>
                        <option value="Data Analytics">Data Analytics</option>
                        <option value="Cyber Security">Cyber Security</option>
                    </select>
                </div>
                <div class="input-group checkbox">
                    <input type="checkbox" id="updates" name="updates" checked />
                    <label for="updates">I want to receive updates directly on WhatsApp</label>
                </div>
                <p>By proceeding, I agree to the <a href="#">Terms & Conditions</a> and <a href="#">Privacy Policy</a>
                    of Starkin Academy.</p>
                <button type="submit" class="submit-btn" id="submitBtn" disabled>Book a Free Session</button>
            </form>
        </div>
    </div>

    <script>
        let otpVerified = false;
        let timerInterval;

        function sendOtp() {
            const phone = document.getElementById('phone').value;
            if (phone) {
                document.querySelector('.phone-group').style.display = 'none';
                document.getElementById('get-otp').style.display = 'none';
                document.getElementById('otp-group').style.display = 'block';

                fetch('send_otp.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: `phone=${phone}`
                });

                startTimer();
            }
        }

        function startTimer() {
            let timer = 60;
            timerInterval = setInterval(() => {
                const minutes = Math.floor(timer / 60);
                const seconds = timer % 60;
                document.getElementById('timer').textContent = `${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
                timer--;

                if (timer < 0) {
                    clearInterval(timerInterval);
                    document.getElementById('go-back').disabled = false;
                }
            }, 1000);
        }

        const otpInputs = document.querySelectorAll('.otp');
        otpInputs.forEach((input, index) => {
            input.addEventListener('input', function () {
                if (this.value.length >= 1 && index < otpInputs.length - 1) {
                    otpInputs[index + 1].focus();
                }
                if (index === otpInputs.length - 1) {
                    verifyOtp();
                }
            });
            input.addEventListener('keydown', function (e) {
                if (e.key === 'Backspace' && index > 0 && this.value.length === 0) {
                    otpInputs[index - 1].focus();
                }
            });
        });

        function verifyOtp() {
            const otp = Array.from(otpInputs).map(input => input.value).join('');
            if (otp.length === 4) {
                fetch('verify_otp.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: `otp=${otp}`
                })
                    .then(response => response.json()) // Parse JSON
                    .then(data => {
                        if (data.success) {
                            otpInputs.forEach(input => {
                                input.classList.add('success');
                                input.classList.remove('error');
                            });
                            otpVerified = true;
                            document.getElementById('submitBtn').disabled = false;
                        } else {
                            otpInputs.forEach(input => {
                                input.classList.add('error');
                                input.classList.remove('success');
                            });
                            setTimeout(() => {
                                otpInputs.forEach(input => input.value = '');
                                otpInputs[0].focus();
                            }, 500);
                        }
                    })
                    .catch(error => console.error("Error verifying OTP:", error));
            }
        }


        function goBack() {
            clearInterval(timerInterval);
            document.getElementById('otp-group').style.display = 'none';
            document.querySelector('.phone-group').style.display = 'flex';
            document.getElementById('get-otp').style.display = 'block';
            otpInputs.forEach(input => input.value = '');
        }

        document.getElementById('sessionForm').addEventListener('submit', function (e) {
            if (!otpVerified) {
                e.preventDefault();
                alert("Please verify OTP before submitting.");
            }
        });
    </script>
</body>

</html>