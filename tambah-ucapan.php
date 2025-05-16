<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Ucapan Ulang Tahun</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&family=Dancing+Script:wght@700&display=swap" rel="stylesheet">
    <style>
        body {
            margin: 0;
            font-family: 'Montserrat', sans-serif;
            background: linear-gradient(135deg, #ff758c 0%, #ff7eb3 100%);
            color: #fff;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .form-container {
            background: rgba(255, 255, 255, 0.95);
            padding: 30px;
            border-radius: 30px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 500px;
        }

        h1 {
            font-family: 'Dancing Script', cursive;
            color: #ff4f81;
            text-align: center;
            margin-bottom: 30px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            color: #ff4f81;
            margin-bottom: 8px;
            font-weight: bold;
        }

        input[type="text"], 
        textarea {
            width: 100%;
            padding: 12px;
            border: 2px solid #ff4f81;
            border-radius: 15px;
            font-family: 'Montserrat', sans-serif;
            font-size: 16px;
            box-sizing: border-box;
        }

        input[type="file"] {
            display: none;
        }

        .file-label {
            display: inline-block;
            padding: 12px 20px;
            background: #ff4f81;
            color: white;
            border-radius: 30px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .file-label:hover {
            background: #ff758c;
            transform: translateY(-2px);
        }

        button {
            width: 100%;
            padding: 15px;
            background: #ff4f81;
            color: white;
            border: none;
            border-radius: 30px;
            font-size: 18px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-family: 'Montserrat', sans-serif;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-top: 20px;
        }

        button:hover {
            background: #ff758c;
            transform: translateY(-3px);
            box-shadow: 0 7px 15px rgba(0, 0, 0, 0.2);
        }

        .selected-file {
            margin-top: 10px;
            color: #ff4f81;
            font-size: 14px;
        }

        .notification {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: rgba(255, 255, 255, 0.95);
            padding: 20px 40px;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            z-index: 1000;
            text-align: center;
            color: #ff4f81;
            font-size: 18px;
            font-weight: bold;
        }

        .notification.show {
            display: block;
            animation: fadeIn 0.5s;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .loading {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: rgba(255, 255, 255, 0.95);
            padding: 30px 50px;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            z-index: 1000;
            text-align: center;
        }

        .loading.show {
            display: block;
        }

        .spinner {
            width: 40px;
            height: 40px;
            margin: 0 auto 15px;
            border: 4px solid #f3f3f3;
            border-top: 4px solid #ff4f81;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        .loading-text {
            color: #ff4f81;
            font-weight: bold;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <div class="notification" id="notification">
        Terima kasih sudah mau mengucapkan! 💖
    </div>
    <div class="loading" id="loading">
        <div class="spinner"></div>
        <div class="loading-text">Mengirim ucapan...</div>
    </div>
    <div class="form-container">
        <h1>Kirim Ucapan Ulang Tahun 💝</h1>
        <form action="process-ucapan.php" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="nama">Nama</label>
                <input type="text" id="nama" name="nama" required>
            </div>
            
            <div class="form-group">
                <label for="ucapan">Ucapan</label>
                <textarea id="ucapan" name="ucapan" rows="4" required></textarea>
            </div>
            
            <div class="form-group">
                <label class="file-label" for="foto">
                    📸 Pilih Foto untuk menjadi Foto Profile
                </label>
                <input type="file" id="foto" name="foto" accept="image/*" required>
                <div class="selected-file" id="selectedFile">Belum ada file dipilih</div>
            </div>
            
            <button type="submit">Kirim Ucapan ✨</button>
        </form>
    </div>

    <script>
        document.getElementById('foto').addEventListener('change', function(e) {
            const fileName = e.target.files[0]?.name || 'Belum ada file dipilih';
            document.getElementById('selectedFile').textContent = fileName;
        });

        document.querySelector('form').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const loading = document.getElementById('loading');
            
            // Show loading
            loading.classList.add('show');
            
            fetch('process-ucapan.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(() => {
                // Hide loading after 1.5 seconds (for better UX)
                setTimeout(() => {
                    loading.classList.remove('show');
                    const notification = document.getElementById('notification');
                    notification.classList.add('show');
                    
                    setTimeout(() => {
                        notification.classList.remove('show');
                        document.querySelector('form').reset();
                        document.getElementById('selectedFile').textContent = 'Belum ada file dipilih';
                    }, 3000);
                }, 1500);
            })
            .catch(error => {
                loading.classList.remove('show');
                console.error('Error:', error);
            });
        });
    </script>
</body>
</html>
