<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceso Biblioteca - CEIP Andrés Manjón</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@400;700&display=swap" rel="stylesheet">
</head>
<body class="login-body">

<div class="login-container">
    <div class="login-visual-panel">
        <img id="dynamic-img" src="" alt="Biblioteca">
        <div class="visual-text">
            <h1 id="visual-title">¡A leer!</h1>
            <p>CEIP Andrés Manjón</p>
        </div>
    </div>

    <div class="login-form-panel">
        <a href="index.php" class="back-to-catalog">← Volver al Catálogo</a>

        <div class="role-selector">
            <button class="tab-btn active" onclick="changeRole('alumno', this)">MiniProfesor</button>
            <button class="tab-btn" onclick="changeRole('profesor', this)">Profesor</button>
            <button class="tab-btn" onclick="changeRole('admin', this)">Admin</button>
        </div>

        <div class="welcome-header">
            <h2 id="welcome-title">Hola, Alumno</h2>
            <p id="welcome-subtext">Ingresa tu nombre y número de carnet.</p>
        </div>

        <form action="validar.php<?php echo (isset($_GET['return_to']) ? '?return_to=' . urlencode($_GET['return_to']) : '') . (isset($_GET['libro']) ? '&libro=' . urlencode($_GET['libro']) : ''); ?>" method="POST" id="main-form">
            <input type="hidden" name="rol" id="role-input" value="alumno">
            
            <div id="input-area">
                </div>

            <button type="submit" class="submit-button">ENTRAR ➔</button>
        </form>

        <div id="register-link-container" class="register-section">
            <p>Eres Alumno <a href="index.php"> Ir a Catalogo </a></p>
        </div>

        <div class="school-footer-logos">
            <img src="img/gobierno-aragon.png" alt="Aragón" onerror="this.style.display='none'">
            <img src="img/andres.jpg" alt="Colegio" onerror="this.style.display='none'">
        </div>
    </div>
</div>

<script>
    const setup = {
        alumno: {
            title: "¡A leer!",
            welcome: "Hola, Bienvenido",
            sub: "Ingresa tu nombre y número de carnet.",
            img: "img/portal_alumno.jpg",
            reg: true,
            fields: `
                <div class="input-box">
                    <label>NOMBRE DEL ALUMNO</label>
                    <input type="text" name="nombre" placeholder="Nombre completo" required>
                </div>
                <div class="input-box">
                    <label>NÚMERO DE CARNET</label>
                    <input type="text" name="carnet" placeholder="Ej: 2026-XYZ" required>
                </div>`
        },
        profesor: {
            title: "Docentes",
            welcome: "Hola, Profesor",
            sub: "Usa tu correo institucional del colegio.",
            img: "img/portal_profesor.png",
            reg: false,
            fields: `
                <div class="input-box">
                    <label>CORREO EMPRESARIAL</label>
                    <input type="email" name="email" placeholder="usuario@colegio.es" required>
                </div>
                <div class="input-box">
                    <label>CONTRASEÑA</label>
                    <input type="password" name="password" placeholder="••••••••" required>
                </div>`
        },
        admin: {
            title: "Gestión",
            welcome: "Hola, Admin",
            sub: "Panel de control del sistema.",
            img: "img/portal_admin.png",
            reg: false,
            fields: `
                <div class="input-box">
                    <label>CORREO DE ADMINISTRADOR</label>
                    <input type="email" name="email" placeholder="admin@sistema.es" required>
                </div>
                <div class="input-box">
                    <label>CONTRASEÑA</label>
                    <input type="password" name="password" placeholder="••••••••" required>
                </div>`
        }
    };

    function changeRole(role, btn) {
        document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');

        const s = setup[role];
        document.getElementById('visual-title').innerText = s.title;
        document.getElementById('welcome-title').innerText = s.welcome;
        document.getElementById('welcome-subtext').innerText = s.sub;
        document.getElementById('dynamic-img').src = s.img;
        document.getElementById('role-input').value = role;
        document.getElementById('input-area').innerHTML = s.fields;
        document.getElementById('register-link-container').style.display = s.reg ? 'block' : 'none';
    }

    window.onload = () => changeRole('alumno', document.querySelector('.tab-btn'));
</script>
<footer class="site-footer">
  <div class="footer-content">
    
    <div class="footer-left">
      <strong>RRAZ studios</strong> &copy; 2026 | <a href="https://www.aepd.es/politica-de-privacidad-y-aviso-legal">Privacy Policy</a>
    </div>

    <div class="footer-right">
      <a href="https://www.instagram.com/ceip_andresmanjon/" class="social-link">
        <img src="img/icono_instagram.png" alt="Instagram"></img>
      </a>
      <a href="https://www.micole.net/zaragoza/zaragoza/colegio-andres-manjon" class="social-link">
        <img src="img/icono_micole.png" alt="Micole"></img>
      </a>
      <a href="http://www.educateca.com/centros/ceip-andres-manjon-z.asp#f5" class="social-link">
        <img src="img/icono_educateca.jpg" alt="Educateca"></img>
      </a>
      <a href="https://www.zaragoza.es/sede/servicio/equipamiento/548" class="social-link">
        <img src="img/icono_ayuntamiento.png" alt="Zaragoza"></img>
      </a>
    </div>
    
  </div>
</footer>

<script src="https://kit.fontawesome.com/your-code.js" crossorigin="anonymous"></script>
</body>

</html>