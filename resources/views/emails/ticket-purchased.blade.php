<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tus boletos — TicketWave</title>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      background-color: #f4f4f4;
      font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
      color: #1a1a1a;
    }

    .wrapper {
      max-width: 620px;
      margin: 32px auto;
      background: #ffffff;
      border-radius: 12px;
      overflow: hidden;
      box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
    }

    /* Header */
    .header {
      background: linear-gradient(135deg, #051F20 0%, #163832 100%);
      padding: 36px 40px;
      text-align: center;
    }

    .header-logo {
      font-size: 28px;
      font-weight: 800;
      color: #ffffff;
      letter-spacing: -0.5px;
    }

    .header-logo span {
      color: #83D5AB;
    }

    .header-subtitle {
      color: #83D5AB;
      font-size: 13px;
      margin-top: 6px;
      letter-spacing: 0.08em;
      text-transform: uppercase;
    }

    /* Greeting */
    .greeting {
      padding: 32px 40px 0;
    }

    .greeting h1 {
      font-size: 22px;
      font-weight: 700;
      color: #0d2b26;
    }

    .greeting p {
      margin-top: 10px;
      color: #555;
      font-size: 15px;
      line-height: 1.6;
    }

    /* Order summary */
    .order-badge {
      margin: 24px 40px 0;
      background: #f0faf5;
      border: 1px solid #b2e8cf;
      border-radius: 10px;
      padding: 16px 20px;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .order-badge-label {
      font-size: 12px;
      color: #5a8a72;
      text-transform: uppercase;
      letter-spacing: 0.08em;
      font-weight: 600;
    }

    .order-badge-value {
      font-size: 18px;
      font-weight: 800;
      color: #0d2b26;
    }

    /* Ticket card */
    .tickets {
      padding: 24px 40px;
      display: flex;
      flex-direction: column;
      gap: 24px;
    }

    .ticket-card {
      border: 1px solid #e0e0e0;
      border-radius: 12px;
      overflow: hidden;
    }

    .ticket-header {
      background: #051F20;
      padding: 16px 20px;
    }

    .ticket-event {
      font-size: 16px;
      font-weight: 700;
      color: #ffffff;
    }

    .ticket-type {
      font-size: 13px;
      color: #83D5AB;
      margin-top: 3px;
    }

    .ticket-body {
      padding: 20px;
      display: flex;
      gap: 20px;
      align-items: flex-start;
    }

    .ticket-qr {
      flex-shrink: 0;
    }

    .ticket-qr img {
      width: 130px;
      height: 130px;
      border-radius: 8px;
      border: 3px solid #e8f5ee;
      display: block;
    }

    .ticket-qr-ref {
      text-align: center;
      font-size: 10px;
      color: #888;
      margin-top: 6px;
      font-family: monospace;
      letter-spacing: 0.05em;
    }

    .ticket-info {
      flex: 1;
    }

    .ticket-info-row {
      display: flex;
      justify-content: space-between;
      padding: 7px 0;
      border-bottom: 1px solid #f0f0f0;
    }

    .ticket-info-row:last-child {
      border-bottom: none;
    }

    .ticket-info-label {
      font-size: 12px;
      color: #888;
    }

    .ticket-info-value {
      font-size: 13px;
      font-weight: 600;
      color: #1a1a1a;
      text-align: right;
      max-width: 200px;
    }

    .ticket-footer {
      background: #f9fafb;
      padding: 12px 20px;
      border-top: 1px solid #e0e0e0;
      font-size: 12px;
      color: #888;
    }

    .ticket-footer strong {
      color: #0d2b26;
    }

    /* Total */
    .total-row {
      margin: 0 40px;
      padding: 20px;
      background: #051F20;
      border-radius: 10px;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .total-label {
      color: #83D5AB;
      font-size: 14px;
      font-weight: 600;
    }

    .total-value {
      color: #ffffff;
      font-size: 22px;
      font-weight: 800;
    }

    /* Instructions */
    .instructions {
      margin: 24px 40px 0;
      padding: 20px;
      background: #fffbeb;
      border: 1px solid #fde68a;
      border-radius: 10px;
    }

    .instructions-title {
      font-size: 13px;
      font-weight: 700;
      color: #92400e;
      margin-bottom: 10px;
    }

    .instructions ul {
      padding-left: 18px;
    }

    .instructions ul li {
      font-size: 13px;
      color: #78350f;
      margin-bottom: 6px;
      line-height: 1.5;
    }

    /* CTA */
    .cta {
      text-align: center;
      padding: 28px 40px;
    }

    .cta a {
      display: inline-block;
      background: #83D5AB;
      color: #051F20;
      font-weight: 700;
      font-size: 15px;
      padding: 14px 36px;
      border-radius: 50px;
      text-decoration: none;
      letter-spacing: 0.02em;
    }

    /* Footer */
    .footer {
      background: #f9fafb;
      border-top: 1px solid #e0e0e0;
      padding: 24px 40px;
      text-align: center;
    }

    .footer p {
      font-size: 12px;
      color: #aaa;
      line-height: 1.7;
    }

    .footer strong {
      color: #555;
    }
  </style>
</head>

<body>
  <div class="wrapper">

    {{-- Header --}}
    <div class="header">
      <div class="header-logo"><span>T</span>icketWave</div>
      <div class="header-subtitle">Confirmación de compra</div>
    </div>

    {{-- Greeting --}}
    <div class="greeting">
      <h1>¡Todo listo, {{ $notifiable->name }}! 🎉</h1>
      <p>Tu compra fue confirmada exitosamente. Guarda este correo — contiene los códigos QR que necesitarás para acceder al evento.</p>
    </div>

    {{-- Order badge --}}
    <div class="order-badge">
      <div>
        <div class="order-badge-label">Número de orden</div>
        <div class="order-badge-value">#{{ $orderId }}</div>
      </div>
      <div style="text-align:right;">
        <div class="order-badge-label">Fecha de compra</div>
        <div style="font-size:14px; font-weight:600; color:#0d2b26;">
          {{ \Carbon\Carbon::now()->locale('es')->translatedFormat('d \d\e F \d\e Y') }}
        </div>
      </div>
    </div>

    {{-- Tickets --}}
    <div class="tickets">
      @foreach($tickets as $ticket)
      <div class="ticket-card">
        <div class="ticket-header">
          <div class="ticket-event">{{ $ticket['evento'] }}</div>
          <div class="ticket-type">{{ $ticket['tipo'] }}</div>
        </div>
        <div class="ticket-body">
          <div class="ticket-qr">
            <img src="data:image/svg+xml;base64,{{ $ticket['qr'] }}" alt="QR {{ $ticket['referencia'] }}">
            <div class="ticket-qr-ref">{{ $ticket['referencia'] }}</div>
          </div>
          <div class="ticket-info">
            <div class="ticket-info-row">
              <span class="ticket-info-label">Evento</span>
              <span class="ticket-info-value">{{ $ticket['evento'] }}</span>
            </div>
            <div class="ticket-info-row">
              <span class="ticket-info-label">Tipo</span>
              <span class="ticket-info-value">{{ $ticket['tipo'] }}</span>
            </div>
            <div class="ticket-info-row">
              <span class="ticket-info-label">Fecha</span>
              <span class="ticket-info-value">{{ $ticket['fecha'] }}</span>
            </div>
            <div class="ticket-info-row">
              <span class="ticket-info-label">Cantidad</span>
              <span class="ticket-info-value">{{ $ticket['cantidad'] }} boleto(s)</span>
            </div>
            <div class="ticket-info-row">
              <span class="ticket-info-label">Precio unitario</span>
              <span class="ticket-info-value">${{ $ticket['precio'] }}</span>
            </div>
            <div class="ticket-info-row">
              <span class="ticket-info-label">Subtotal</span>
              <span class="ticket-info-value" style="color:#0d2b26; font-weight:800;">${{ $ticket['subtotal'] }}</span>
            </div>
          </div>
        </div>
        <div class="ticket-footer">
          <strong>¿Cómo usar tu QR?</strong> Presenta este código en el acceso del evento.
          Cada QR es válido para <strong>{{ $ticket['cantidad'] }} persona(s)</strong>.
        </div>
      </div>
      @endforeach
    </div>

    {{-- Total --}}
    <div class="total-row">
      <div class="total-label">Total pagado</div>
      <div class="total-value">${{ $total }}</div>
    </div>

    {{-- Instrucciones --}}
    <div class="instructions">
      <div class="instructions-title">⚠️ Información importante</div>
      <ul>
        <li>Guarda este correo o toma captura de pantalla de tus códigos QR.</li>
        <li>Cada código QR es único e intransferible.</li>
        <li>Preséntalo en la entrada del evento desde tu dispositivo móvil o impreso.</li>
        <li>Llega con anticipación para evitar filas.</li>
      </ul>
    </div>

    {{-- CTA --}}
    <div class="cta">
      <a href="{{ route('mis-boletos') }}">Ver mis boletos en TicketWave</a>
    </div>

    {{-- Footer --}}
    <div class="footer">
      <p>
        Este correo fue enviado a <strong>{{ $notifiable->email }}</strong><br>
        porque realizaste una compra en TicketWave.<br><br>
        © {{ date('Y') }} TicketWave — Todos los derechos reservados
      </p>
    </div>

  </div>
</body>

</html>