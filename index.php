<?php
require_once __DIR__ . '/config/firebase-config.php';

// Fetch ucapan from Firebase Realtime Database without ordering
$snapshot = $database->getReference('ucapan')->getValue();

// Convert to array and sort by timestamp manually
$ucapan = [];
if ($snapshot) {
    $ucapan = array_values($snapshot);
    usort($ucapan, function($a, $b) {
        return $b['timestamp'] - $a['timestamp'];
    });
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Happy Birthdayy💖</title>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&family=Dancing+Script:wght@700&display=swap" rel="stylesheet">
  <style>
    body {
      margin: 0;
      font-family: 'Montserrat', sans-serif;
      background: linear-gradient(135deg, #ff758c 0%, #ff7eb3 100%);
      color: #fff;
      overflow-x: hidden;
      min-height: 100vh;
    }
    .page {
      display: none;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
      padding: 20px;
      text-align: center;
      animation: fadeIn 0.5s ease-in;
    }
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }
    .active {
      display: flex;
    }
    h1, h2 {
      font-family: 'Dancing Script', cursive;
      text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
      margin-bottom: 30px;
    }
    button {
      margin-top: 30px;
      padding: 15px 40px;
      background: rgba(255,255,255,0.9);
      color: #ff4f81;
      border: none;
      border-radius: 30px;
      font-size: 18px;
      cursor: pointer;
      transition: all 0.3s ease;
      font-family: 'Montserrat', sans-serif;
      font-weight: bold;
      text-transform: uppercase;
      letter-spacing: 1px;
    }
    button:hover {
      transform: translateY(-3px);
      box-shadow: 0 7px 15px rgba(0,0,0,0.2);
      background: #fff;
    }
    img {
      max-width: 350px;
      border-radius: 30px;
      box-shadow: 0 10px 30px rgba(0,0,0,0.2);
      transition: transform 0.3s ease;
    }
    img:hover {
      transform: scale(1.05);
    }
    .gallery {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 20px;
      margin: 30px 0;
    }
    .gallery img {
      width: 150px;
      height: 150px;
      object-fit: cover;
      border-radius: 15px;
      border: 3px solid rgba(255,255,255,0.8);
    }
    .card {
      background: rgba(255,255,255,0.95);
      color: #ff4f81;
      padding: 30px 40px;
      border-radius: 30px;
      box-shadow: 0 10px 30px rgba(0,0,0,0.1);
      backdrop-filter: blur(10px);
      margin-top: 30px;
      min-width: 300px;
    }
    input[type="date"], input[type="time"] {
      padding: 12px 20px;
      border: 2px solid rgba(255,255,255,0.8);
      border-radius: 15px;
      background: rgba(255,255,255,0.1);
      color: white;
      font-size: 16px;
      margin: 10px 0;
      width: 250px;
    }
    label {
      display: block;
      margin: 15px 0;
      font-size: 18px;
      cursor: pointer;
      transition: transform 0.2s ease;
    }
    label:hover {
      transform: translateX(5px);
    }
    .cursor-heart {
      position: fixed;
      pointer-events: none;
      z-index: 9999;
      font-size: 24px;
      filter: drop-shadow(0 0 5px rgba(255,255,255,0.5));
      animation: floatHeart 1.2s ease-out forwards;
      transform-origin: center center;
      transform: translate(-50%, -50%);
    }
    @keyframes floatHeart {
      0% { opacity: 1; transform: translate(-50%, -50%) scale(0.5); }
      50% { opacity: 0.8; transform: translate(-50%, -80%) scale(1.2); }
      100% { opacity: 0; transform: translate(-50%, -120%) scale(1); }
    }
    .flip-card {
      background-color: transparent;
      width: 300px;
      height: 200px;
      perspective: 1000px;
      margin: 20px;
    }
    .flip-card-inner {
      position: relative;
      width: 100%;
      height: 100%;
      text-align: center;
      transition: transform 0.6s;
      transform-style: preserve-3d;
    }
    .flip-card:hover .flip-card-inner {
      transform: rotateY(180deg);
    }
    .flip-card-front, .flip-card-back {
      position: absolute;
      width: 100%;
      height: 100%;
      backface-visibility: hidden;
      display: flex;
      justify-content: center;
      align-items: center;
      border-radius: 15px;
      box-shadow: 0 10px 30px rgba(0,0,0,0.2);
      padding: 20px;
    }
    .flip-card-front {
      background: rgba(255,255,255,0.9);
      color: #ff4f81;
    }
    .flip-card-back {
      background: #ff4f81;
      color: #fff;
      transform: rotateY(180deg);
    }
    .cake-container {
      display: flex;
      justify-content: center;
      align-items: center;
      margin: 20px 0;
    }
    .cake {
      width: 100px;
      height: 150px;
      border-radius: 10px;
      position: relative;
    }
    .candle {
      width: 10px;
      height: 50px;
      background: #fff;
      position: absolute;
      top: -50px;
      left: 50%;
      transform: translateX(-50%);
      border-radius: 5px;
    }
    .flame {
      width: 15px;
      height: 15px;
      background: #ff0;
      position: absolute;
      top: -15px;
      left: 50%;
      transform: translateX(-50%);
      border-radius: 50%;
      animation: flicker 0.5s infinite alternate;
    }
    @keyframes flicker {
      0% { transform: translateX(-50%) scale(1); }
      100% { transform: translateX(-50%) scale(1.2); }
    }
    .wish-instruction {
      font-size: 18px;
      margin: 20px 0;
    }
    .final-message {
      text-align: center;
      margin-top: 20px;
    }
    .timeline {
      max-width: 800px;
      margin: 0 auto;
      position: relative;
      padding: 20px;
    }
    
    .timeline::before {
      content: '';
      position: absolute;
      left: 40px;
      width: 2px;
      height: 100%;
      background: rgba(255,255,255,0.8);
    }
    
    .timeline-card {
      display: flex;
      align-items: center;
      padding: 20px;
      background: rgba(255,255,255,0.95);
      border-radius: 15px;
      margin: 30px 0 30px 80px;
      position: relative;
      box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    
    .timeline-dot {
      width: 16px;
      height: 16px;
      background: #ff4f81;
      border: 3px solid white;
      border-radius: 50%;
      position: absolute;
      left: -50px;
      top: 50%;
      transform: translateY(-50%);
      z-index: 1;
    }
    
    .timeline-img {
      width: 100px;
      height: 100px;
      border-radius: 50%;
      object-fit: cover;
      margin-right: 20px;
      border: 3px solid #ff4f81;
      flex-shrink: 0;
    }
    
    .timeline-content {
      flex: 1;
    }
    
    .timeline-name {
      color: #ff4f81;
      font-weight: bold;
      margin-bottom: 10px;
      font-size: 1.2em;
    }
    
    .timeline-message {
      color: #666;
      font-size: 0.9em;
      line-height: 1.4;
    }

    .sticker-container {
      display: flex;
      justify-content: center;
      gap: 20px;
      margin: 20px 0;
      flex-wrap: wrap;
    }

    .sticker {
      width: 100px;
      height: 100px;
      object-fit: contain;
      filter: drop-shadow(0 0 5px rgba(255,255,255,0.3));
      animation: bounce 2s infinite ease-in-out;
    }

    @keyframes bounce {
      0%, 100% { transform: translateY(0); }
      50% { transform: translateY(-10px); }
    }

    @media (max-width: 768px) {
      .page {
        padding: 15px;
      }

      h1 {
        font-size: 2.2em;
      }

      h2 {
        font-size: 1.8em;
      }

      p {
        font-size: 1rem;
        padding: 0 15px;
      }

      img {
        max-width: 90%;
      }

      button {
        padding: 12px 30px;
        font-size: 16px;
        width: 80%;
        max-width: 300px;
      }

      .gallery {
        grid-template-columns: 1fr;
        gap: 15px;
      }

      .gallery img {
        width: 200px;
        height: 200px;
        margin: 0 auto;
      }

      .timeline {
        padding: 10px;
      }

      .timeline::before {
        left: 20px;
      }

      .timeline-card {
        margin-left: 40px;
        padding: 15px;
      }

      .timeline-dot {
        left: -30px;
      }

      .timeline-img {
        width: 80px;
        height: 80px;
      }

      .timeline-name {
        font-size: 1.1em;
      }

      .timeline-message {
        font-size: 0.85em;
      }

      .cake-container .cake {
        width: 150px;
        height: 150px;
      }

      .final-message h3 {
        font-size: 1.6em;
      }

      .final-message p {
        font-size: 1rem;
      }

      .sticker {
        width: 80px;
        height: 80px;
      }
    }

    @media (max-width: 480px) {
      h1 {
        font-size: 1.8em;
      }

      h2 {
        font-size: 1.5em;
      }

      .timeline-card {
        margin-left: 35px;
      }

      .timeline-img {
        width: 60px;
        height: 60px;
      }

      .cake-container .cake {
        width: 120px;
        height: 120px;
      }
    }
  </style>
</head>
<body>

  <!-- Halaman 1 -->
  <div class="page active" id="page1">
    <h1>Happy Birthdayy 💖</h1>
    <img src="assets/1.gif" alt="Vanisa">
    <button onclick="nextPage()">Lanjutkan</button>
  </div>

  <!-- Halaman 2 -->
  <div class="page" id="page2">
    <h2>For My Princess</h2>
    <p>Semoga panjang Umur, Sehat selalu, Dimudahkan segala urusan dunianya, Patuh pada orang tua. <br>Semoga menjadi pribadi yang lebih baik lagi dari sebelumnya, perlu di ingat harus bisa menabungg ya gesyaa(Haruss) hahaha. <br>Semoga aku menjadi pria terakhir dalam hidupmu, tempatmu pulang, dan orang yang selalu menjagamu sampai akhir waktu.<br> Selamat Ulang Tahun Cintakuuu💕</p>
    <!-- Stiker GIF ditambahkan di sini -->
    <div class="gallery">
        <img src="assets/2.gif" alt="foto1">
        <img src="assets/3.jpeg" alt="foto2">
        <img src="assets/11.jpg" alt="foto3">
        <img src="assets/4.jpeg" alt="foto4">
        <img src="assets/5.jpeg" alt="foto5">
        <img src="assets/6.jpeg" alt="foto6">
        <img src="assets/7.jpeg" alt="foto7">
        <img src="assets/8.jpeg" alt="foto8">
        <img src="assets/9.jpeg" alt="foto9">
        <img src="assets/10.jpeg" alt="foto10">
    </div>
    <button onclick="nextPage()">Lanjutkan</button>
  </div>

  <!-- Halaman 3 -->
  <div class="page" id="page3">
    <h2>Messages From Friends 💌</h2>
    <div class="timeline">
        <?php if(!empty($ucapan)): foreach($ucapan as $message): ?>
            <div class="timeline-card">
                <div class="timeline-dot"></div>
                <img src="<?php echo htmlspecialchars($message['foto_url']); ?>" 
                     alt="Foto <?php echo htmlspecialchars($message['nama']); ?>" 
                     class="timeline-img">
                <div class="timeline-content">
                    <div class="timeline-name"><?php echo htmlspecialchars($message['nama']); ?></div>
                    <div class="timeline-message">
                        "<?php echo htmlspecialchars($message['ucapan']); ?>"
                    </div>
                </div>
            </div>
        <?php endforeach; endif; ?>
    </div>
    <button onclick="nextPage()">Lanjutkan</button>
  </div>

  <!-- Halaman 4 -->
  <div class="page" id="page4">
    <h2>Make a Wish! 🌟</h2>
    <div class="cake-container">
        <div class="cake" style="
            position: relative;
            width: 200px;
            height: 200px;
            margin: 50px auto;
        ">
            <!-- Top layer -->
            <div class="layer" style="
                position: absolute;
                bottom: 140px;
                left: 50%;
                transform: translateX(-50%);
                width: 140px;
                height: 60px;
                background: #ff85a2;
                border-radius: 10px 10px 0 0;
            ">
                <div class="icing" style="
                    width: 100%;
                    height: 15px;
                    background: linear-gradient(90deg, #fff 70%, #ffe5e5 100%);
                    border-radius: 10px 10px 0 0;
                "></div>
                <div class="candle" style="
                    position: absolute;
                    top: -30px;
                    left: 50%;
                    transform: translateX(-50%);
                    width: 10px;
                    height: 40px;
                    background: linear-gradient(90deg, #fff 70%, #ffe5e5 100%);
                    border-radius: 5px;
                    cursor: pointer;
                ">
                    <div class="flame" style="
                        position: absolute;
                        top: -15px;
                        left: 50%;
                        transform: translateX(-50%);
                        width: 15px;
                        height: 15px;
                        background: #ffeb3b;
                        border-radius: 50%;
                        box-shadow: 0 0 10px #ffeb3b;
                        animation: flicker 0.5s infinite alternate;
                    "></div>
                </div>
            </div>
            <!-- Middle layer -->
            <div class="layer" style="
                position: absolute;
                bottom: 70px;
                left: 50%;
                transform: translateX(-50%);
                width: 170px;
                height: 70px;
                background: #ff6b8b;
            ">
                <div class="icing" style="
                    width: 100%;
                    height: 15px;
                    background: linear-gradient(90deg, #fff 70%, #ffe5e5 100%);
                "></div>
            </div>
            <!-- Bottom layer -->
            <div class="layer" style="
                position: absolute;
                bottom: 0;
                left: 50%;
                transform: translateX(-50%);
                width: 200px;
                height: 70px;
                background: #ff4f81;
                border-radius: 0 0 10px 10px;
            ">
                <div class="icing" style="
                    width: 100%;
                    height: 15px;
                    background: linear-gradient(90deg, #fff 70%, #ffe5e5 100%);
                "></div>
            </div>
        </div>
    </div>
    <p class="wish-instruction">Klik lilin untuk membuat permohonan!</p>
    <div class="final-message" style="display: none;">
        <h3>Selamat Ulang Tahun Vanisa! 🎉</h3>
        <p>Semoga semua keinginanmu terkabul!</p>
        <p>Aku mencintaimu! ❤️</p>
    </div>
    <button onclick="restartCard()">Mulai Ulang ❤️</button>
  </div>

  <script>
    let current = 1;
    function nextPage() {
      document.getElementById(`page${current}`).classList.remove('active');
      current++;
      document.getElementById(`page${current}`).classList.add('active');
    }

    let lastHeartTime = 0;
    document.addEventListener('mousemove', function(e) {
      const now = Date.now();
      if (now - lastHeartTime > 100) {
        const heart = document.createElement('div');
        heart.classList.add('cursor-heart');
        heart.style.left = `${e.clientX}px`;
        heart.style.top = `${e.clientY}px`;
        heart.textContent = '❤';
        document.body.appendChild(heart);
        
        heart.addEventListener('animationend', () => heart.remove());
        lastHeartTime = now;
      }
    });

    document.querySelector('.candle').addEventListener('click', function() {
      document.querySelector('.final-message').style.display = 'block';
    });

    function restartCard() {
        // Sembunyikan semua halaman
        document.querySelectorAll('.page').forEach(page => {
            page.classList.remove('active');
        });
        
        // Tampilkan halaman pertama
        document.getElementById('page1').classList.add('active');
        
        // Reset current page ke 1
        current = 1;
        
        // Sembunyikan pesan final jika masih terbuka
        document.querySelector('.final-message').style.display = 'none';
    }
  </script>
</body>
</html>