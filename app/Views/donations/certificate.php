<div style="border:8px double #d97706;padding:40px 30px;background:#fffbeb;border-radius:12px;text-align:center;position:relative;">
  
  <div style="width:60px;height:60px;background:#059669;color:#fff;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 15px;font-size:1.8rem;box-shadow:0 4px 6px rgba(0,0,0,0.1);">
    <i class="fa-solid fa-award"></i>
  </div>

  <h3 style="font-family:'Playfair Display', serif;font-size:1.2rem;letter-spacing:2px;color:#064e3b;margin-bottom:4px;text-transform:uppercase;">
    Yayasan Peduli Kasih Sesama
  </h3>
  <div style="font-size:0.75rem;color:#78350f;letter-spacing:1px;margin-bottom:20px;">
    SK KEMENKUMHAM RI NO. AHU-0012485.AH.01.04.TAHUN 2018
  </div>

  <h1 style="font-family:'Playfair Display', serif;font-size:2.4rem;color:#b45309;margin-bottom:15px;letter-spacing:1px;">
    SERTIFIKAT PENGHARGAAN
  </h1>

  <div style="font-size:0.9rem;color:#451a03;margin-bottom:10px;">
    Nomor: CERT/YPK/<?= date('Y') ?>/<?= substr($donation['donation_code'], -7) ?>
  </div>

  <p style="font-size:1rem;color:#78350f;margin-bottom:20px;font-style:italic;">
    Dengan rasa hormat dan terima kasih yang mendalam, piagam ini dianugerahkan kepada:
  </p>

  <h2 style="font-size:2.2rem;font-weight:800;color:#0f172a;margin-bottom:15px;border-bottom:2px solid #d97706;display:inline-block;padding-bottom:5px;">
    <?= $donation['is_anonymous'] ? 'Hamba Allah (Muhsinin)' : e($donation['donor_name']) ?>
  </h2>

  <p style="max-width:600px;margin:15px auto 35px;line-height:1.7;color:#451a03;font-size:1rem;">
    Atas ketulusan hati, kepedulian, dan kontribusi nyata dalam menyalurkan donasi senilai <strong><?= format_rupiah($donation['amount']) ?></strong> untuk keberhasilan program sosial kemanusiaan:
    <br><strong style="color:#064e3b;font-size:1.1rem;">"<?= e($donation['campaign_title']) ?>"</strong>
  </p>

  <div style="display:flex;justify-content:space-around;margin-top:40px;align-items:flex-end;">
    <div style="text-align:center;">
      <div style="font-size:0.85rem;color:#78350f;">Tanggal Terbit:</div>
      <div style="font-weight:700;"><?= format_date($donation['verified_at'] ?? $donation['created_at']) ?></div>
      <div style="margin-top:20px;font-size:0.75rem;color:#78350f;"><i class="fa-solid fa-qrcode" style="font-size:2.5rem;"></i><br>Verifikasi Digital Sah</div>
    </div>

    <div style="text-align:center;">
      <div style="font-size:0.85rem;color:#78350f;margin-bottom:50px;">Direktur Eksekutif Yayasan,</div>
      <div style="font-weight:800;font-size:1.05rem;text-decoration:underline;">Drs. H. Mulyono Santoso</div>
      <div style="font-size:0.8rem;color:#78350f;">Ketua Pembina & Pengurus Pusat</div>
    </div>
  </div>

</div>
