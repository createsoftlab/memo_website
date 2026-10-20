
(function () {
  var cards = [
    {title:"Neon Cyberpunk City 1",subtitle:"Futuristic Metropolis",badge:"Trending",image:"https://images.unsplash.com/photo-1519501025264-65ba15a82390?q=80&w=1000&auto=format&fit=crop",description:"Explore the bustling neon-drenched streets of a futuristic megalopolis where technology and humanity intertwine under perpetual rain."},
    {title:"Serene Alpine Peaks",subtitle:"Mountain Wilderness",badge:"Nature",image:"https://images.unsplash.com/photo-1464822759023-fed622ff2c3b?q=80&w=1000&auto=format&fit=crop",description:"Majestic snow-capped mountain peaks reflecting crisp morning sunlight over crystal clear glacial alpine lakes."},
    {title:"Deep Cosmos Nebula",subtitle:"Interstellar Odyssey",badge:"Astronomy",image:"https://images.unsplash.com/photo-1506703719100-a0f3a48c0f86?q=80&w=1000&auto=format&fit=crop",description:"Vibrant clouds of interstellar gas and cosmic dust forming newborn stellar systems billions of light years away."},
    {title:"Zen Minimalist Architecture",subtitle:"Modern Design",badge:"Design",image:"https://images.unsplash.com/photo-1600585154340-be6161a56a0c?q=80&w=1000&auto=format&fit=crop",description:"Clean lines, natural light, and tranquil structural harmony designed for mindful living and quiet contemplation."},
    {title:"Mystic Autumn Forest",subtitle:"Golden Woodlands",badge:"Seasonal",image:"https://images.unsplash.com/photo-1507525428034-b723cf961d3e?q=80&w=1000&auto=format&fit=crop",description:"A tranquil winding path through ancient woodlands covered in a vibrant carpet of crisp orange and golden amber autumn leaves."}
  ];

  var root = document.getElementById('nx3d');
  var $ = function (id) { return document.getElementById(id); };
  var carousel = $('nxCarousel'), dotsBox = $('nxDots');
  var cur = 0, dragging = false, startX = 0;

  // Move modals to <body> so position:fixed is never trapped by a parent transform
  document.body.appendChild($('nxDetail'));

  function esc(s) {
    return String(s).replace(/[&<>"']/g, function (c) {
      return {'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c];
    });
  }

  function renderCards() {
    carousel.innerHTML = '';
    var step = 360 / cards.length;
    var radius = Math.max(220, Math.min(340, root.clientWidth * 0.3));
    cards.forEach(function (c, i) {
      var el = document.createElement('div');
      el.className = 'card glass';
      el.style.transform = 'rotateY(' + (i * step) + 'deg) translateZ(' + radius + 'px)';
      el.innerHTML =
        '<img src="' + esc(c.image) + '" alt="' + esc(c.title) + '" onerror="this.src=\'https://placehold.co/600x400/1e293b/818cf8?text=Image+Unavailable\'">' +
        '<div class="shade"></div>' +
        '<div class="top"><span class="badge">' + esc(c.badge) + '</span><div class="ico">↗</div></div>' +
        '<div class="info"><span class="sub">' + esc(c.subtitle) + '</span><h3>' + esc(c.title) + '</h3><p>' + esc(c.description) + '</p></div>';
      el.addEventListener('click', function (e) {
        e.stopPropagation();
        if (cur === i) openDetail(c); else { cur = i; update(); }
      });
      carousel.appendChild(el);
    });
  }

  function renderDots() {
    dotsBox.innerHTML = '';
    cards.forEach(function (_, i) {
      var d = document.createElement('button');
      d.type = 'button';
      d.className = 'dot';
      d.setAttribute('aria-label', 'Go to card ' + (i + 1));
      d.addEventListener('click', function () { cur = i; update(); });
      dotsBox.appendChild(d);
    });
  }

  function update() {
    carousel.style.transform = 'rotateY(' + (-cur * (360 / cards.length)) + 'deg)';
    Array.prototype.forEach.call(carousel.children, function (el, i) {
      el.style.filter = i === cur ? 'none' : 'brightness(.6) blur(2px)';
      el.style.zIndex = i === cur ? 30 : 10;
    });
    Array.prototype.forEach.call(dotsBox.children, function (d, i) {
      d.className = 'dot' + (i === cur ? ' on' : '');
    });
  }

  function next() { cur = (cur + 1) % cards.length; update(); }
  function prev() { cur = (cur - 1 + cards.length) % cards.length; update(); }

  $('nxNext').addEventListener('click', next);
  $('nxPrev').addEventListener('click', prev);

  // Keyboard only while the section is on screen
  var visible = true;
  if ('IntersectionObserver' in window) {
    new IntersectionObserver(function (e) { visible = e[0].isIntersecting; }).observe(root);
  }
  document.addEventListener('keydown', function (e) {
    if (!visible || /INPUT|TEXTAREA/.test(document.activeElement.tagName)) return;
    if (e.key === 'ArrowRight') next();
    if (e.key === 'ArrowLeft') prev();
  });

  // Touch swipe
  carousel.addEventListener('touchstart', function (e) { startX = e.touches[0].clientX; dragging = true; }, {passive:true});
  carousel.addEventListener('touchmove', function (e) {
    if (!dragging) return;
    var diff = startX - e.touches[0].clientX;
    if (Math.abs(diff) > 50) { diff > 0 ? next() : prev(); dragging = false; }
  }, {passive:true});
  carousel.addEventListener('touchend', function () { dragging = false; });

  // Modals
  function openModal(id) { $(id).classList.add('open'); }
  function closeModal(id) { $(id).classList.remove('open'); }

  function openDetail(c) {
    $('nxMImg').src = c.image;
    $('nxMBadge').textContent = c.badge;
    $('nxMSub').textContent = c.subtitle;
    $('nxMTitle').textContent = c.title;
    $('nxMDesc').textContent = c.description;
    openModal('nxDetail');
  }

  document.querySelectorAll('[data-close]').forEach(function (b) {
    b.addEventListener('click', function () { closeModal(b.getAttribute('data-close')); });
  });
  $('nxDetail').addEventListener('click', function (e) { if (e.target === $('nxDetail')) closeModal('nxDetail'); });
  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') closeModal('nxDetail');
  });

  var rt;
  window.addEventListener('resize', function () {
    clearTimeout(rt);
    rt = setTimeout(function () { renderCards(); update(); }, 150);
  });

  renderCards(); renderDots(); update();
})();



// hart js 


document.addEventListener('DOMContentLoaded', () => {
    const container = document.getElementById('section-canvas-container');
    if (!container) return;

    let secScene, secCamera, secRenderer, secControls;
    let secHeartGroup, secCoreMesh, secParticleSystem;
    let secClock = new THREE.Clock();
    
    let secThemeIndex = 0;
    let secAnimationSpeed = 1.0;
    let secIsExploded = false;
    let secBeatIntensity = 1.0;
    let secTargetBeatIntensity = 1.0;

    const secThemes = [
        { name: "Ruby Rose", core: 0xff1744, particles: 0xff5252, ambient: 0xff8a80, bg: 0x050505 },
        { name: "Cyan Nebula", core: 0x00e5ff, particles: 0x76ff03, ambient: 0x18ffff, bg: 0x020508 },
        { name: "Amethyst Glow", core: 0xd500f9, particles: 0xff80ab, ambient: 0xea80fc, bg: 0x060208 },
        { name: "Golden Sunset", core: 0xffab00, particles: 0xffea00, ambient: 0xffd740, bg: 0x080602 }
    ];

    function getHeartPoint(t, scale = 1) {
        const x = 16 * Math.pow(Math.sin(t), 3);
        const y = 13 * Math.cos(t) - 5 * Math.cos(2 * t) - 2 * Math.cos(3 * t) - Math.cos(4 * t);
        return new THREE.Vector3(x * 0.15 * scale, y * 0.15 * scale, 0);
    }

    secScene = new THREE.Scene();
    secScene.fog = new THREE.FogExp2(secThemes[secThemeIndex].bg, 0.035);

    const width = container.clientWidth;
    const height = container.clientHeight;

    secCamera = new THREE.PerspectiveCamera(60, width / height, 0.1, 1000);
    secCamera.position.set(0, 0, 7.5);

    secRenderer = new THREE.WebGLRenderer({ antialias: true, alpha: true, powerPreference: "high-performance" });
    secRenderer.setSize(width, height);
    secRenderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
    container.appendChild(secRenderer.domElement);

    secControls = new THREE.OrbitControls(secCamera, secRenderer.domElement);
    secControls.enableDamping = true;
    secControls.dampingFactor = 0.05;
    secControls.maxDistance = 15;
    secControls.minDistance = 3;
    secControls.autoRotate = true;
    secControls.autoRotateSpeed = 1.0;

    const ambientLight = new THREE.AmbientLight(secThemes[secThemeIndex].ambient, 1.5);
    secScene.add(ambientLight);

    const pointLight1 = new THREE.PointLight(secThemes[secThemeIndex].core, 4, 50);
    pointLight1.position.set(0, 2, 4);
    secScene.add(pointLight1);

    secHeartGroup = new THREE.Group();
    secScene.add(secHeartGroup);

    // Build Heart Core
    const heartShape = new THREE.Shape();
    const x = 0, y = 0;
    heartShape.moveTo(x + 5, y + 5);
    heartShape.bezierCurveTo(x + 5, y + 5, x + 4, y, x, y);
    heartShape.bezierCurveTo(x - 6, y, x - 6, y + 7, x - 6, y + 7);
    heartShape.bezierCurveTo(x - 6, y + 11, x - 3, y + 15.4, x + 5, y + 19);
    heartShape.bezierCurveTo(x + 13, y + 15.4, x + 16, y + 11, x + 16, y + 7);
    heartShape.bezierCurveTo(x + 16, y + 7, x + 16, y, x + 10, y);
    heartShape.bezierCurveTo(x + 7, y, x + 5, y + 5, x + 5, y + 5);

    const geometry = new THREE.ExtrudeGeometry(heartShape, {
        depth: 2.5, bevelEnabled: true, bevelSegments: 8, steps: 4, bevelSize: 1.2, bevelThickness: 1.2
    });
    geometry.center();
    geometry.scale(0.18, 0.18, 0.18);

    const material = new THREE.MeshPhysicalMaterial({
        color: secThemes[secThemeIndex].core, emissive: secThemes[secThemeIndex].core, emissiveIntensity: 0.4,
        roughness: 0.2, metalness: 0.8, transmission: 0.3, ior: 1.5, transparent: true, opacity: 0.92
    });

    secCoreMesh = new THREE.Mesh(geometry, material);
    secCoreMesh.rotation.z = Math.PI;
    secHeartGroup.add(secCoreMesh);

    // Build Particles
    const particleCount = 2000;
    const pGeo = new THREE.BufferGeometry();
    const positions = [], originals = [];

    for (let i = 0; i < particleCount; i++) {
        const t = Math.random() * Math.PI * 2;
        const u = (Math.random() - 0.5) * 2.5;
        const base = getHeartPoint(t, 1.3);
        const px = base.x + (Math.random() - 0.5) * 0.3;
        const py = base.y + (Math.random() - 0.5) * 0.3;
        const pz = u + (Math.random() - 0.5) * 0.3;
        positions.push(px, py, pz);
        originals.push(px, py, pz);
    }
    pGeo.setAttribute('position', new THREE.Float32BufferAttribute(positions, 3));
    pGeo.setAttribute('originalPosition', new THREE.Float32BufferAttribute(originals, 3));

    const canvasTex = document.createElement('canvas');
    canvasTex.width = 64; canvasTex.height = 64;
    const ctx = canvasTex.getContext('2d');
    const grad = ctx.createRadialGradient(32, 32, 0, 32, 32, 32);
    grad.addColorStop(0, 'rgba(255,255,255,1)');
    grad.addColorStop(0.3, 'rgba(255,100,150,0.8)');
    grad.addColorStop(1, 'rgba(0,0,0,0)');
    ctx.fillStyle = grad;
    ctx.fillRect(0, 0, 64, 64);

    secParticleSystem = new THREE.Points(pGeo, new THREE.PointsMaterial({
        color: secThemes[secThemeIndex].particles, size: 0.12, map: new THREE.CanvasTexture(canvasTex),
        transparent: true, blending: THREE.AdditiveBlending, depthWrite: false
    }));
    secHeartGroup.add(secParticleSystem);

    function secAnimate() {
        requestAnimationFrame(secAnimate);
        const elapsedTime = secClock.getElapsedTime();
        secControls.update();

        secBeatIntensity += (secTargetBeatIntensity - secBeatIntensity) * 0.1;
        if (Math.abs(secTargetBeatIntensity - secBeatIntensity) < 0.01) secTargetBeatIntensity = 1.0;

        const heartbeat = 1.0 + Math.sin(elapsedTime * 3.5 * secAnimationSpeed) * 0.08 * secBeatIntensity;
        if (secHeartGroup) {
            secHeartGroup.scale.set(heartbeat, heartbeat, heartbeat);
            secHeartGroup.rotation.y = elapsedTime * 0.4 * secAnimationSpeed;
        }

        if (secParticleSystem) {
            const pos = secParticleSystem.geometry.attributes.position.array;
            const orig = secParticleSystem.geometry.attributes.originalPosition.array;
            for (let i = 0; i < pos.length; i += 3) {
                const idx = i / 3;
                if (secIsExploded) {
                    pos[i] += (orig[i] * 3 - pos[i]) * 0.05;
                    pos[i+1] += (orig[i+1] * 3 - pos[i+1]) * 0.05;
                    pos[i+2] += (orig[i+2] * 3 - pos[i+2]) * 0.05;
                } else {
                    const wave = Math.sin(elapsedTime * 4 + idx) * 0.02;
                    pos[i] = orig[i] * (1 + wave);
                    pos[i+1] = orig[i+1] * (1 + wave);
                    pos[i+2] = orig[i+2] + wave;
                }
            }
            secParticleSystem.geometry.attributes.position.needsUpdate = true;
        }
        secRenderer.render(secScene, secCamera);
    }
    secAnimate();

    window.addEventListener('resize', () => {
        if (!container) return;
        const w = container.clientWidth;
        const h = container.clientHeight;
        secCamera.aspect = w / h;
        secCamera.updateProjectionMatrix();
        secRenderer.setSize(w, h);
    });

    // Safe Event Listeners (Elements පවතින විට පමණක් ක්‍රියාත්මක වේ)
    const beatBtn = document.getElementById('sec-beat-btn');
    if (beatBtn) {
        beatBtn.addEventListener('click', () => { secTargetBeatIntensity = 2.2; });
    }

    const colorBtn = document.getElementById('sec-color-btn');
    if (colorBtn) {
        colorBtn.addEventListener('click', () => {
            secThemeIndex = (secThemeIndex + 1) % secThemes.length;
            const theme = secThemes[secThemeIndex];
            secScene.background = new THREE.Color(theme.bg);
            secScene.fog.color = new THREE.Color(theme.bg);
            if (secCoreMesh) {
                secCoreMesh.material.color.setHex(theme.core);
                secCoreMesh.material.emissive.setHex(theme.core);
            }
            if (secParticleSystem) secParticleSystem.material.color.setHex(theme.particles);
            
            const bpmSection = document.getElementById('heart-bpm-section');
            if (bpmSection) bpmSection.textContent = `Theme: ${theme.name}`;
        });
    }

    const explodeBtn = document.getElementById('sec-explode-btn');
    if (explodeBtn) {
        explodeBtn.addEventListener('click', () => {
            secIsExploded = !secIsExploded;
            explodeBtn.classList.toggle('bg-rose-500/30', secIsExploded);
        });
    }

    const resetBtn = document.getElementById('sec-reset-btn');
    if (resetBtn) {
        resetBtn.addEventListener('click', () => {
            secIsExploded = false;
            secAnimationSpeed = 1.0;
            secControls.reset();
            const bpmSection = document.getElementById('heart-bpm-section');
            if (bpmSection) bpmSection.textContent = 'BPM: 75';
        });
    }

    const fullscreenBtn = document.getElementById('section-fullscreen-btn');
    if (fullscreenBtn) {
        fullscreenBtn.addEventListener('click', () => {
            const wrapper = container.parentElement;
            if (!document.fullscreenElement) {
                wrapper.requestFullscreen().catch(err => console.error(err));
            } else {
                if (document.exitFullscreen) document.exitFullscreen();
            }
        });
    }
});




document.addEventListener('DOMContentLoaded', () => {
    const container = document.getElementById('cat-multiflower-canvas-container');
    if (!container) return;

    let scene, camera, renderer, controls;
    let dovesGroup = [];
    let ambientParticles;
    let clock = new THREE.Clock();
    
    let themeIndex = 0;
    let isBurst = false;

    // Themes for Dove Glow / Light
    const themes = [
        { name: "Pure White Doves", body: 0xffffff, glow: 0xe0f7fa },
        { name: "Golden Light Doves", body: 0xfff9c4, glow: 0xffecb3 },
        { name: "Soft Silver Doves", body: 0xe0e0e0, glow: 0xc5cae9 },
        { name: "Peaceful Blue Doves", body: 0xe1f5fe, glow: 0x81d4fa }
    ];

    scene = new THREE.Scene();

    const width = container.clientWidth;
    const height = container.clientHeight;

    camera = new THREE.PerspectiveCamera(60, width / height, 0.1, 1000);
    camera.position.set(0, 0, 7);

    renderer = new THREE.WebGLRenderer({ antialias: true, alpha: true, powerPreference: "high-performance" });
    renderer.setSize(width, height);
    renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
    container.appendChild(renderer.domElement);

    controls = new THREE.OrbitControls(camera, renderer.domElement);
    controls.enableDamping = true;
    controls.dampingFactor = 0.05;
    controls.maxDistance = 12;
    controls.minDistance = 2;
    controls.autoRotate = true;
    controls.autoRotateSpeed = 0.6;

    // Lighting
    const ambientLight = new THREE.AmbientLight(0xffffff, 1.5);
    scene.add(ambientLight);

    const pointLight = new THREE.PointLight(0xffffff, 4, 50);
    pointLight.position.set(3, 5, 4);
    scene.add(pointLight);

    const masterGroup = new THREE.Group();
    scene.add(masterGroup);

    // Function to create a stylized 3D Dove
    function createDove(theme, scale = 1) {
        const doveGroup = new THREE.Group();

        // Dove Body
        const bodyGeo = new THREE.ConeGeometry(0.25 * scale, 0.8 * scale, 5);
        bodyGeo.rotateX(Math.PI / 2);
        const bodyMat = new THREE.MeshStandardMaterial({ 
            color: theme.body, 
            roughness: 0.3, 
            metalness: 0.1,
            emissive: theme.glow,
            emissiveIntensity: 0.2 
        });
        const body = new THREE.Mesh(bodyGeo, bodyMat);
        doveGroup.add(body);

        // Wings (Left & Right)
        const wingGeo = new THREE.BoxGeometry(0.9 * scale, 0.05 * scale, 0.4 * scale);
        const wingMat = new THREE.MeshStandardMaterial({ 
            color: theme.body, 
            roughness: 0.3,
            emissive: theme.glow,
            emissiveIntensity: 0.15,
            side: THREE.DoubleSide 
        });

        const leftWing = new THREE.Mesh(wingGeo, wingMat);
        leftWing.position.set(0.4 * scale, 0, 0);
        leftWing.userData = { isWing: true, side: 1 };
        doveGroup.add(leftWing);

        const rightWing = new THREE.Mesh(wingGeo, wingMat);
        rightWing.position.set(-0.4 * scale, 0, 0);
        rightWing.userData = { isWing: true, side: -1 };
        doveGroup.add(rightWing);

        // Head & Beak
        const headGeo = new THREE.SphereGeometry(0.15 * scale, 12, 12);
        const head = new THREE.Mesh(headGeo, bodyMat);
        head.position.set(0, 0.25 * scale, 0.4 * scale);
        doveGroup.add(head);

        doveGroup.userData = { wings: [leftWing, rightWing] };
        return doveGroup;
    }

    // Generate Multiple Doves Cluster
    const doveCount = 5;
    for (let i = 0; i < doveCount; i++) {
        const theme = themes[themeIndex];
        const dove = createDove(theme, i === 0 ? 1.2 : 0.8);

        if (i === 0) {
            dove.position.set(0, 0, 0);
        } else {
            const u = Math.random();
            const v = Math.random();
            const theta = u * 2.0 * Math.PI;
            const phi = Math.acos(2.0 * v - 1.0);
            const r = 1.8 + Math.random() * 1.2;
            
            dove.position.x = r * Math.sin(phi) * Math.cos(theta);
            dove.position.y = r * Math.sin(phi) * Math.sin(theta);
            dove.position.z = r * Math.cos(phi);
            
            dove.rotation.set(Math.random() * Math.PI, Math.random() * Math.PI, 0);
        }

        masterGroup.add(dove);
        dovesGroup.push(dove);
    }

    // Floating Ambient Peace Light Particles
    const pGeo = new THREE.BufferGeometry();
    const positions = [];
    for (let i = 0; i < 200; i++) {
        positions.push((Math.random() - 0.5) * 12, (Math.random() - 0.5) * 12, (Math.random() - 0.5) * 12);
    }
    pGeo.setAttribute('position', new THREE.Float32BufferAttribute(positions, 3));
    ambientParticles = new THREE.Points(pGeo, new THREE.PointsMaterial({
        color: 0xffffff, size: 0.06, transparent: true, opacity: 0.7, blending: THREE.AdditiveBlending
    }));
    scene.add(ambientParticles);

    function animate() {
        requestAnimationFrame(animate);
        const time = clock.getElapsedTime();
        controls.update();

        masterGroup.rotation.y = time * 0.15;

        // Animate Doves and Flapping Wings
        dovesGroup.forEach((dove, dIndex) => {
            dove.rotation.x += 0.001;
            dove.rotation.z += 0.001;

            if (dove.userData && dove.userData.wings) {
                dove.userData.wings.forEach(wing => {
                    const side = wing.userData.side;
                    wing.rotation.z = side * (Math.sin(time * 6 + dIndex) * 0.4 + 0.2);
                });
            }
        });

        if (ambientParticles) {
            ambientParticles.rotation.y -= 0.001;
        }

        renderer.render(scene, camera);
    }
    animate();

    window.addEventListener('resize', () => {
        if (!container) return;
        const w = container.clientWidth;
        const h = container.clientHeight;
        camera.aspect = w / h;
        camera.updateProjectionMatrix();
        renderer.setSize(w, h);
    });

    // Safe Event Listeners (Null Safety Checks සමඟ)
    const colorBtn = document.getElementById('cat-multiflower-color-btn');
    if (colorBtn) {
        colorBtn.addEventListener('click', () => {
            themeIndex = (themeIndex + 1) % themes.length;
            const theme = themes[themeIndex];
            
            dovesGroup.forEach(dove => {
                dove.children.forEach(child => {
                    if (child.material) {
                        child.material.color.setHex(theme.body);
                        if (child.material.emissive) {
                            child.material.emissive.setHex(theme.glow);
                        }
                    }
                });
            });
            
            const statusEl = document.getElementById('cat-multiflower-status');
            if (statusEl) statusEl.textContent = theme.name;
        });
    }

    const bloomBtn = document.getElementById('cat-multiflower-bloom-btn');
    if (bloomBtn) {
        bloomBtn.addEventListener('click', () => {
            isBurst = !isBurst;
            bloomBtn.classList.toggle('bg-amber-500/30', isBurst);
        });
    }

    const resetBtn = document.getElementById('cat-multiflower-reset-btn');
    if (resetBtn) {
        resetBtn.addEventListener('click', () => {
            isBurst = false;
            if (bloomBtn) bloomBtn.classList.remove('bg-amber-500/30');
            controls.reset();
            const statusEl = document.getElementById('cat-multiflower-status');
            if (statusEl) statusEl.textContent = 'Peace Doves';
        });
    }
});




document.addEventListener('DOMContentLoaded', () => {
    const container = document.getElementById('memory-3d-container');
    if (!container) return;

    let scene, camera, renderer, controls;
    let memoryGroup = new THREE.Group();
    let clock = new THREE.Clock();

    // Memory cards data list
    const memories = [
        { title: "Always in our hearts", subtitle: "Remembering " },
        { title: "Forever Missed", subtitle: "A beautiful soul" },
        { title: "Cherished Moments", subtitle: "Rest in Peace" },
        { title: "Unforgettable", subtitle: "In loving memory" },
        { title: "Eternal Light", subtitle: "Gone but not forgotten" },
        { title: "Precious Smiles", subtitle: "Always with us" }
    ];

    scene = new THREE.Scene();

    const width = container.clientWidth;
    const height = container.clientHeight;

    camera = new THREE.PerspectiveCamera(60, width / height, 0.1, 1000);
    // කැමරාව ටිකක් පසුපසට ගෙන ඇත (කාඩ්පත් විශාල වූ විට ඒවා කෝණිකව හොඳින් පෙනීමට)
    camera.position.set(0, 0, 7.0);

    renderer = new THREE.WebGLRenderer({ antialias: true, alpha: true });
    renderer.setSize(width, height);
    renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
    renderer.setClearColor(0x000000, 0); // Transparent Background
    container.appendChild(renderer.domElement);

    // OrbitControls for smooth drag & swipe support
    controls = new THREE.OrbitControls(camera, renderer.domElement);
    controls.enableDamping = true;
    controls.dampingFactor = 0.05;
    controls.autoRotate = true;          
    controls.autoRotateSpeed = 0.8;      
    controls.enableZoom = false;

    // Lighting setup
    scene.add(new THREE.AmbientLight(0xffffff, 2.0));
    const pointLight = new THREE.PointLight(0xffffff, 3.5, 50);
    pointLight.position.set(0, 5, 5);
    scene.add(pointLight);

    scene.add(memoryGroup);

    // Create 3D memory cards dynamically (ප්‍රමාණය විශාල කර ඇත)
    memories.forEach((mem, index) => {
        const cardCanvas = document.createElement('canvas');
        cardCanvas.width = 800;   // 👈 Resolution වැඩි කළා (Sharp පෙනුම සඳහා)
        cardCanvas.height = 500;
        const ctx = cardCanvas.getContext('2d');

        ctx.clearRect(0, 0, cardCanvas.width, cardCanvas.height);

        // Glassmorphism background
        ctx.fillStyle = 'rgba(58, 39, 1, 0.87)'; 
        ctx.roundRect(15, 15, 770, 470, 32);
        ctx.fill();

        // Border
        ctx.strokeStyle = 'rgb(24, 16, 1)';
        ctx.lineWidth = 8;
        ctx.stroke();

        // Card Text Details (විශාල ප්‍රමාණයේ අකුරු)
        ctx.fillStyle = '#ffffff';
        ctx.font = 'bold 50px sans-serif';
        ctx.textAlign = 'center';
        ctx.fillText(mem.title, 400, 200);

        ctx.fillStyle = '#cbd5e1';
        ctx.font = '34px sans-serif';
        ctx.fillText(mem.subtitle, 400, 270);

        ctx.fillStyle = '#ffd000';
        ctx.font = 'italic 26px sans-serif';
        ctx.fillText("❤️ List Your Memory", 400, 380);

        const texture = new THREE.CanvasTexture(cardCanvas);
        
        // 👈 මෙන්න මෙතැනින් තමයි කාඩ්පතේ 3D ප්‍රමාණය (Width, Height) ලොකු කළේ (පෙර 2.7, 1.7 සිට 3.6, 2.2 දක්වා)
        const geometry = new THREE.PlaneGeometry(3.6, 2.2); 
        const material = new THREE.MeshStandardMaterial({ 
            map: texture, 
            side: THREE.DoubleSide,
            transparent: true,
            roughness: 0.1,
            metalness: 0.1
        });

        const card = new THREE.Mesh(geometry, material);

        // Circular ring radius වැඩි කර කාඩ් එකිනෙක අතර ඉඩ තැබීම
        const angle = (index / memories.length) * Math.PI * 2;
        const radius = 3.8; // 👈 රවුමේ ප්‍රමාණය වැඩි කළා (කාඩ් ලොකු වූ නිසා ගැටීම වළක්වා ගැනීමට)
        card.position.x = Math.cos(angle) * radius;
        card.position.z = Math.sin(angle) * radius;
        card.position.y = (Math.sin(index * 2) * 0.4);

        card.rotation.y = -angle + Math.PI / 2;
        card.userData = { originalY: card.position.y };
        memoryGroup.add(card);
    });

    // Animation Loop
    function animate() {
        requestAnimationFrame(animate);
        const time = clock.getElapsedTime();
        controls.update();

        memoryGroup.children.forEach((card, idx) => {
            card.position.y = card.userData.originalY + Math.sin(time * 2.2 + idx) * 0.1;
        });

        renderer.render(scene, camera);
    }
    animate();

    // Responsive screen resize
    window.addEventListener('resize', () => {
        if (!container) return;
        const w = container.clientWidth;
        const h = container.clientHeight;
        camera.aspect = w / h;
        camera.updateProjectionMatrix();
        renderer.setSize(w, h);
    });
});