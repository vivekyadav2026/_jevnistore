<?php require_once 'includes/header.php'; ?>

    <section class="section" style="background: var(--bg-primary); padding: 8rem 0; min-height: 80vh; font-family: var(--font);">
        <div class="container" style="max-width: 800px; padding: 0 20px;">
            <span style="font-size: 0.75rem; letter-spacing: 3px; color: var(--accent); text-transform: uppercase; font-weight: 600; display: block; margin-bottom: 1rem;">CUSTOMER CARE</span>
            <h1 style="font-size: clamp(2rem, 5vw, 3rem); font-weight: 300; letter-spacing: 3px; text-transform: uppercase; color: var(--text-primary); margin-bottom: 3rem; line-height: 1.2;">EXCHANGE PORTAL</h1>
            
            <div style="color: var(--text-secondary); line-height: 1.8; font-size: 0.95rem; display: flex; flex-direction: column; gap: 2rem;">
                <p>
                    Welcome to the JEVANI Exchange Portal. We want you to be completely satisfied with your purchase. If you need to exchange an item, please enter your order details below to start the process.
                </p>
                
                <!-- Exchange Form -->
                <form action="" method="POST" style="background: var(--bg-secondary); padding: 30px; border-radius: 12px; border: 1px solid var(--border-color); display: flex; flex-direction: column; gap: 1.5rem; margin-top: 1rem;">
                    <div style="display: flex; flex-direction: column; gap: 8px;">
                        <label style="font-size: 0.75rem; letter-spacing: 1.5px; text-transform: uppercase; font-weight: 600; color: var(--text-primary);">Order Number</label>
                        <input type="text" placeholder="e.g. #JV-1024" required style="width: 100%; padding: 12px; background: rgba(0,0,0,0.2); border: 1px solid var(--border-color); color: #fff; outline: none; font-family: var(--font); border-radius: 8px;">
                    </div>
                    <div style="display: flex; flex-direction: column; gap: 8px;">
                        <label style="font-size: 0.75rem; letter-spacing: 1.5px; text-transform: uppercase; font-weight: 600; color: var(--text-primary);">Email Address</label>
                        <input type="email" placeholder="you@example.com" required style="width: 100%; padding: 12px; background: rgba(0,0,0,0.2); border: 1px solid var(--border-color); color: #fff; outline: none; font-family: var(--font); border-radius: 8px;">
                    </div>
                    <button type="submit" class="btn" style="width: 100%; padding: 14px; font-weight: 600; letter-spacing: 2px; text-transform: uppercase;" onclick="event.preventDefault(); alert('Exchange request submitted successfully. Our team will contact you shortly.');">Submit Exchange Request</button>
                </form>
            </div>
        </div>
    </section>

<?php require_once 'includes/footer.php'; ?>
