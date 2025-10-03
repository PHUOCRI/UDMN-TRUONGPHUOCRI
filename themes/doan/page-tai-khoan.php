<?php
/*
 * Template Name: Tài khoản
 * Description: Trang đăng nhập/đăng ký tài khoản đơn giản, responsive, không có bình luận.
 */

get_header();
?>
<style>
:root{ --acc-primary: var(--primary-color, #0ea5a0); --acc-accent: var(--accent-color, #ff7a59); --acc-dark: var(--secondary-color, #1f2a37); --acc-muted:#6b7280; --acc-bg:#f8fafc; --acc-border:#e5e7eb; }
.account-page { padding: 56px 0 72px; background: var(--acc-bg); }
.account-page .container { max-width: 1100px; margin: 0 auto; padding: 0 16px; }
.account-page .section-header { text-align:center; margin-bottom: 24px; position:relative; }
.account-page .section-title { font-size: clamp(28px,3vw,36px); font-weight: 900; color: var(--acc-dark); margin:0; letter-spacing:.2px; }
.account-page .section-header:after{ content:""; display:block; width: 90px; height: 4px; background: linear-gradient(90deg,var(--acc-accent),#ffd166); margin: 12px auto 0; border-radius: 999px; }

/* cards */
.account-page .card{ background:#fff; border:1px solid #eef2f7; border-radius: 18px; box-shadow: 0 10px 28px rgba(15,23,42,.06); backdrop-filter:saturate(1.05); transition: transform .25s ease, box-shadow .25s ease; }
.account-page .card:hover{ transform: translateY(-2px); box-shadow: 0 14px 36px rgba(15,23,42,.10); }
.account-page .auth-wrapper{ display:grid; grid-template-columns: 1fr 1fr; gap:18px; }
.account-page .auth-card{ padding: 22px 22px 20px; }
.account-page .card-title{ margin:0 0 12px; font-size:18px; font-weight:900; color:var(--acc-dark); letter-spacing:.2px; }
.account-page .muted{ color: var(--acc-muted); font-size: 14px; }

/* forms */
.account-page .form{ display:grid; gap:12px; }
.account-page label{ font-weight:700; font-size:13px; color:#374151; }
.account-page input[type="text"],
.account-page input[type="email"],
.account-page input[type="password"]{ width:100%; border:1px solid var(--acc-border); border-radius:12px; padding:12px 14px; font-size:14px; transition: border-color .2s ease, box-shadow .2s ease, transform .2s ease; background:#fff; }
.account-page input:focus{ outline:none; border-color: var(--acc-primary); box-shadow: 0 0 0 4px color-mix(in srgb, var(--acc-primary) 20%, transparent); transform: translateY(-1px); }
.account-page .row{ display:flex; gap:12px; }
.account-page .row > *{ flex:1; }

/* buttons */
.account-page .btn{ display:inline-flex; align-items:center; justify-content:center; gap:8px; border:none; border-radius: 999px; padding: 12px 18px; font-weight:900; letter-spacing:.2px; cursor:pointer; color:#fff; background: var(--acc-dark); position:relative; overflow:hidden; transition: transform .2s ease, box-shadow .2s ease, filter .2s ease; }
.account-page .btn.primary{ background: linear-gradient(135deg, var(--acc-accent), color-mix(in srgb, var(--acc-accent) 60%, #ff9a7f 40%)); box-shadow: 0 10px 20px color-mix(in srgb, var(--acc-accent) 30%, transparent); }
.account-page .btn:hover{ transform: translateY(-1px); box-shadow: 0 12px 26px rgba(2,6,23,.10); filter: brightness(1.02); }
.account-page .btn:active{ transform: translateY(0); }
.account-page .btn:before{ content:""; position:absolute; inset:0; background: radial-gradient(500px 200px at var(--mx,0) var(--my,0), rgba(255,255,255,.25), transparent 60%); opacity:0; transition:opacity .25s ease; }
.account-page .btn:hover:before{ opacity:.8; }

/* notices */
.account-page .notice{ margin: 0 0 16px; padding: 12px 14px; border-radius: 12px; border:1px solid transparent; animation: slideIn .35s ease both; }
.account-page .notice-success{ background:#ecfdf5; border-color:#a7f3d0; color:#065f46; }
.account-page .notice-error{ background:#fef2f2; border-color:#fecaca; color:#991b1b; }

/* dashboard */
.account-page .dashboard{ padding: 22px; display:grid; gap:6px; }
.account-page .role-badge{ display:inline-flex; align-items:center; gap:6px; background: color-mix(in srgb, var(--acc-primary) 12%, #e6fffb); color: var(--acc-primary); border:1px solid color-mix(in srgb, var(--acc-primary) 30%, transparent); padding: 6px 10px; border-radius: 999px; font-weight:800; font-size:12px; text-transform:uppercase; letter-spacing:.2px; }
.account-page .dash-actions{ display:flex; gap:10px; flex-wrap:wrap; margin-top: 8px; }
.account-page .dash-actions a{ text-decoration:none; }

/* password toggle */
.account-page .pass-wrapper{ position: relative; }
.account-page .toggle-pass{ position:absolute; right:10px; top:50%; transform: translateY(-50%); border:0; background:transparent; color:#9ca3af; cursor:pointer; padding:6px; border-radius:8px; transition: color .2s ease, background .2s ease; }
.account-page .toggle-pass:hover{ color: var(--acc-dark); background: #f1f5f9; }

@media (max-width: 992px){ .account-page .auth-card{ padding: 18px; } }
@media (max-width: 768px){ .account-page .auth-wrapper{ grid-template-columns: 1fr; } .account-page .section-title{ font-size:26px; } }

@keyframes slideIn{ from{ opacity:0; transform: translateY(-6px);} to{ opacity:1; transform: translateY(0);} }
</style>
<main id="primary" class="site-main account-page">
  <section>
    <div class="container">
      <header class="section-header">
        <h1 class="section-title"><?php esc_html_e('Tài khoản', 'doan'); ?></h1>
      </header>

      <?php
      // Messages
      $acc = isset($_GET['acc']) ? sanitize_text_field($_GET['acc']) : '';
      $msg = isset($_GET['msg']) ? sanitize_text_field($_GET['msg']) : '';
      if ($acc === 'login_ok') {
        echo '<div class="notice notice-success">' . esc_html__('Đăng nhập thành công.', 'doan') . '</div>';
      } elseif ($acc === 'registered') {
        echo '<div class="notice notice-success">' . esc_html__('Đăng ký thành công. Bạn đã được đăng nhập.', 'doan') . '</div>';
      } elseif ($acc === 'error' && $msg) {
        echo '<div class="notice notice-error">' . esc_html($msg) . '</div>';
      }
      ?>

      <?php if (is_user_logged_in()) : $user = wp_get_current_user(); $roles = (array) $user->roles; $role = !empty($roles) ? $roles[0] : 'subscriber'; ?>
        <div class="card dashboard">
          <div style="display:flex; align-items:center; justify-content:space-between; gap:10px; flex-wrap:wrap;">
            <div>
              <h3 class="card-title" style="margin-bottom:6px;"><?php echo esc_html(sprintf(__('Xin chào, %s!', 'doan'), $user->display_name ?: $user->user_login)); ?></h3>
              <span class="role-badge"><i class="fas fa-user-shield"></i> <?php echo esc_html( ucfirst( translate_user_role( $role ) ) ); ?></span>
            </div>
            <div class="dash-actions">
              <?php if ( current_user_can('edit_posts') ) : ?>
                <a class="btn" href="<?php echo esc_url(admin_url()); ?>"><?php esc_html_e('Bảng điều khiển', 'doan'); ?></a>
              <?php endif; ?>
              <a class="btn" href="<?php echo esc_url(admin_url('profile.php')); ?>"><?php esc_html_e('Hồ sơ', 'doan'); ?></a>
              <a class="btn primary" href="<?php echo esc_url(wp_logout_url(home_url('/'))); ?>"><?php esc_html_e('Đăng xuất', 'doan'); ?></a>
            </div>
          </div>
        </div>
      <?php else : ?>
        <div class="auth-wrapper">
          <div class="card auth-card">
            <h3 class="card-title"><?php esc_html_e('Đăng nhập', 'doan'); ?></h3>
            <form class="form" method="post" action="" onmousemove="this.querySelector('.btn')?.style.setProperty('--mx', event.offsetX+'px');this.querySelector('.btn')?.style.setProperty('--my', event.offsetY+'px');">
              <?php wp_nonce_field('acc_login_action', 'acc_login_nonce'); ?>
              <input type="hidden" name="redirect_to" value="<?php echo esc_url( get_permalink() ); ?>">
              <input type="hidden" name="acc_action" value="login">
              <div>
                <label for="acc_login_user"><?php esc_html_e('Tên đăng nhập hoặc Email', 'doan'); ?></label>
                <input type="text" id="acc_login_user" name="acc_user" required placeholder="username hoặc email">
              </div>
              <div class="pass-wrapper">
                <label for="acc_login_pass"><?php esc_html_e('Mật khẩu', 'doan'); ?></label>
                <input type="password" id="acc_login_pass" name="acc_pass" required placeholder="••••••••">
                <button type="button" class="toggle-pass" aria-label="<?php esc_attr_e('Ẩn/Hiện mật khẩu','doan'); ?>" onclick="(function(b){var i=b.previousElementSibling;i.type=i.type==='password'?'text':'password';b.innerHTML=i.type==='password'?'<i class=\'fas fa-eye\'></i>':'<i class=\'fas fa-eye-slash\'></i>';})(this)"><i class="fas fa-eye"></i></button>
              </div>
              <div class="row" style="align-items:center; justify-content:space-between;">
                <label class="muted"><input type="checkbox" name="remember" value="1"> <?php esc_html_e('Ghi nhớ tôi', 'doan'); ?></label>
                <button type="submit" class="btn primary"><?php esc_html_e('Đăng nhập', 'doan'); ?></button>
              </div>
            </form>
          </div>

          <div class="card auth-card">
            <h3 class="card-title"><?php esc_html_e('Đăng ký', 'doan'); ?></h3>
            <form class="form" method="post" action="" onmousemove="this.querySelector('.btn')?.style.setProperty('--mx', event.offsetX+'px');this.querySelector('.btn')?.style.setProperty('--my', event.offsetY+'px');">
              <?php wp_nonce_field('acc_register_action', 'acc_register_nonce'); ?>
              <input type="hidden" name="redirect_to" value="<?php echo esc_url( get_permalink() ); ?>">
              <input type="hidden" name="acc_action" value="register">
              <div>
                <label for="acc_reg_name"><?php esc_html_e('Họ và tên', 'doan'); ?></label>
                <input type="text" id="acc_reg_name" name="acc_name" placeholder="Nguyễn Văn A">
              </div>
              <div class="row">
                <div>
                  <label for="acc_reg_user"><?php esc_html_e('Tên đăng nhập', 'doan'); ?></label>
                  <input type="text" id="acc_reg_user" name="acc_user" required placeholder="username">
                </div>
                <div>
                  <label for="acc_reg_email">Email</label>
                  <input type="email" id="acc_reg_email" name="acc_email" required placeholder="email@domain.com">
                </div>
              </div>
              <div class="row">
                <div>
                  <div class="pass-wrapper">
                    <label for="acc_reg_pass"><?php esc_html_e('Mật khẩu', 'doan'); ?></label>
                    <input type="password" id="acc_reg_pass" name="acc_pass" required placeholder="••••••••">
                    <button type="button" class="toggle-pass" aria-label="<?php esc_attr_e('Ẩn/Hiện mật khẩu','doan'); ?>" onclick="(function(b){var i=b.previousElementSibling;i.type=i.type==='password'?'text':'password';b.innerHTML=i.type==='password'?'<i class=\'fas fa-eye\'></i>':'<i class=\'fas fa-eye-slash\'></i>';})(this)"><i class="fas fa-eye"></i></button>
                  </div>
                </div>
                <div>
                  <div class="pass-wrapper">
                    <label for="acc_reg_pass2"><?php esc_html_e('Xác nhận mật khẩu', 'doan'); ?></label>
                    <input type="password" id="acc_reg_pass2" name="acc_pass2" required placeholder="••••••••">
                    <button type="button" class="toggle-pass" aria-label="<?php esc_attr_e('Ẩn/Hiện mật khẩu','doan'); ?>" onclick="(function(b){var i=b.previousElementSibling;i.type=i.type==='password'?'text':'password';b.innerHTML=i.type==='password'?'<i class=\'fas fa-eye\'></i>':'<i class=\'fas fa-eye-slash\'></i>';})(this)"><i class="fas fa-eye"></i></button>
                  </div>
                </div>
              </div>
              <button type="submit" class="btn primary"><?php esc_html_e('Đăng ký', 'doan'); ?></button>
            </form>
          </div>
        </div>
      <?php endif; ?>
    </div>
  </section>
</main>
<?php get_footer(); ?>
