<!-- Save this file as success.php (or success.html if you don't need the PHP bits) -->
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Report Submitted • DrainWatch</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  <style>
    :root{
      --bg:#0b1220; --card:#0f1a2b; --muted:#9fb0c3; --accent:#22c55e; --accent-ink:#052e18; --ink:#e6eef7; --btn:#1e293b; --btn-ink:#e2e8f0;
    }
    *{box-sizing:border-box}
    html,body{height:100%}
    body{margin:0; font-family:Inter, system-ui, -apple-system, Segoe UI, Roboto, Helvetica, Arial, "Apple Color Emoji", "Segoe UI Emoji"; background:radial-gradient(1200px 800px at 70% -10%, #1b2a45 0%, #0b1220 60%); color:var(--ink); display:grid; place-items:center;}
    .wrap{width:100%; max-width:720px; padding:28px;}
    .card{background:linear-gradient(180deg, rgba(255,255,255,.04), rgba(255,255,255,.02)); border:1px solid rgba(255,255,255,.08); box-shadow:0 20px 60px rgba(0,0,0,.35), inset 0 1px 0 rgba(255,255,255,.06); border-radius:24px; padding:28px; position:relative; overflow:hidden}
    .badge{display:inline-flex; align-items:center; gap:10px; background:linear-gradient(180deg, #0b1a12, #0a1810); color:#b8f3cf; border:1px solid rgba(34,197,94,.35); padding:8px 12px; border-radius:999px; font-size:12px; letter-spacing:.14em; text-transform:uppercase; font-weight:700}
    .check{width:72px; height:72px; border-radius:999px; display:grid; place-items:center; background:radial-gradient(65% 65% at 50% 30%, rgba(34,197,94,.5), rgba(34,197,94,.15)); border:1px solid rgba(34,197,94,.45); box-shadow:0 0 0 8px rgba(34,197,94,.08), 0 12px 30px rgba(0,0,0,.35)}
    h1{margin:16px 0 8px; font-size:28px; line-height:1.2}
    p{margin:0; color:var(--muted)}
    .ref{margin-top:14px; font-weight:600; color:#c9e8d6}
    .actions{display:flex; flex-wrap:wrap; gap:12px; margin-top:22px}
    .btn{appearance:none; border:none; padding:12px 16px; border-radius:14px; background:var(--btn); color:var(--btn-ink); font-weight:600; cursor:pointer; text-decoration:none; display:inline-flex; align-items:center; gap:10px}
    .btn.primary{background:linear-gradient(180deg, #1b9c5f, #169154); border:1px solid rgba(34,197,94,.5)}
    .btn:disabled{opacity:.5; cursor:not-allowed}
    .muted{color:var(--muted); font-size:12px; margin-top:14px}
    .grid{display:grid; grid-template-columns:72px 1fr; gap:18px; align-items:center}
    .footer{margin-top:22px; display:flex; justify-content:space-between; align-items:center; gap:12px; flex-wrap:wrap}
    .timer{font-variant-numeric:tabular-nums; font-weight:700}
    .confetti{position: absolute;
  inset: 0;
  width: 100%;
  height: 100%;}
  </style>
</head>
<body>
  <div class="wrap">
    <div class="card">
      <canvas class="confetti" id="confetti" aria-hidden="true"></canvas>
      <div class="grid">
        <div class="check" aria-hidden="true">
          <svg width="34" height="34" viewBox="0 0 24 24" fill="none" aria-hidden="true">
            <path d="M20 6L9 17l-5-5" stroke="#b8f3cf" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
        </div>
        <div>
          <span class="badge" role="status" aria-live="polite">Success</span>
          <h1>Report submitted successfully</h1>
          <p>Thank you for helping keep our drains clear. Your submission has been recorded in DrainWatch.</p>
          <!-- Optional dynamic reference number via PHP (safe to keep if this file is .php) -->
          <?php $ref = isset($_GET['ref']) ? preg_replace('/[^A-Za-z0-9-_.]/','', $_GET['ref']) : '';
          if ($ref) { echo '<div class="ref">Reference ID: <code>'.htmlspecialchars($ref).'</code></div>'; } ?>
          <div class="actions">
            <a class="btn primary" href="<?php echo $ref ? 'report.php?id='.urlencode($ref) : '#'; ?>" <?php echo $ref? '' : 'aria-disabled="true"'; ?>>
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M4 19.5V4.5a1.5 1.5 0 0 1 1.5-1.5h9.75L20 7.75V19.5A1.5 1.5 0 0 1 18.5 21h-13A1.5 1.5 0 0 1 4 19.5Z" stroke="currentColor" stroke-width="1.7"/><path d="M15 3v5h5" stroke="currentColor" stroke-width="1.7"/></svg>
              View Report
            </a>
            <a class="btn" href="report_form.php">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M12 5v14M5 12h14" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/></svg>
              Submit Another
            </a>
            <a class="btn" href="dashboard.php">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M3 10.5 12 3l9 7.5V20a1 1 0 0 1-1 1h-6v-6H10v6H4a1 1 0 0 1-1-1v-9.5Z" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/></svg>
              Go to Dashboard
            </a>
          </div>
          <div class="muted">You’ll be redirected to the dashboard in <span class="timer" id="timer">6</span> seconds.</div>
        </div>
      </div>
      
    </div>
  </div>

  <script>
    // Countdown + redirect
    const timerEl = document.getElementById('timer');
    let seconds = 6;
    const to = setInterval(() => {
      seconds--; timerEl.textContent = seconds;
      if (seconds <= 0) { clearInterval(to); window.location.href = 'index.html'; }
    }, 1000);

    // Lightweight confetti
    const canvas = document.getElementById('confetti');
    const ctx = canvas.getContext('2d');
    function resize(){ canvas.width = canvas.offsetWidth; canvas.height = canvas.offsetHeight }
    window.addEventListener('resize', resize); resize();

    const pieces = Array.from({length: 140}).map(() => ({
      x: Math.random()*canvas.width,
      y: -20 - Math.random()*canvas.height,
      s: 4+Math.random()*6,
      v: 1+Math.random()*2.5,
      r: Math.random()*Math.PI,
      w: 8+Math.random()*14
    }));
    function tick(){
      ctx.clearRect(0,0,canvas.width,canvas.height);
      pieces.forEach(p => {
        p.y += p.v; p.r += 0.03; if (p.y>canvas.height+20){ p.y=-20; p.x=Math.random()*canvas.width }
        ctx.save(); ctx.translate(p.x, p.y); ctx.rotate(p.r);
        ctx.fillStyle = ['#22c55e','#16a34a','#86efac','#34d399'][p.s%4];
        ctx.fillRect(-p.w/2,-p.s/2,p.w,p.s); ctx.restore();
      });
      requestAnimationFrame(tick);
    }
    tick();
  </script>
</body>
</html>
