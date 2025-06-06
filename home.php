<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tomato - Your Dashboard</title>
    <link rel="stylesheet" href="../styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>
<body>
    <canvas id="particle-canvas"></canvas>

    <?php include('../components/navbar.php'); ?>

    <header id="hero">
        <div class="container">
            <h1>Welcome back, <?php echo htmlspecialchars($_SESSION['username'] ?? 'Guest'); ?>!</h1>
            <p>Your favorite food, delivered fast & fresh.</p>
            <div class="search-bar">
                <input type="text" placeholder="Search restaurants or cuisines..." id="search-input">
                <button class="search-btn"><i class="fas fa-search"></i></button>
                <div class="search-suggestions" id="search-suggestions"></div>
            </div>
            <a href="./restaurantList.php" class="explore-btn">Explore Restaurants</a>
        </div>
    </header>

    <section id="promo-banner">
        <div class="container">
            <h2>Special Offer!</h2>
            <p>Get 20% off your first order! Hurry, offer ends in:</p>
            <div id="countdown-timer"></div>
            <a href="#" class="promo-btn">Order Now</a>
        </div>
    </section>

    <section id="categories">
        <div class="container">
            <h2>What are you craving?</h2>
            <div class="category-list">
                <div class="category-card" data-category="biryani">🍛 Biryani</div>
                <div class="category-card" data-category="fast-food">🍔 Fast Food</div>
                <div class="category-card" data-category="chinese">🍜 Chinese</div>
                <div class="category-card" data-category="tandoori">🍢 Tandoori</div>
                <div class="category-card" data-category="south-indian">🍽️ South Indian</div>
                <div class="category-card" data-category="desserts">🍨 Desserts</div>
            </div>
        </div>
    </section>

    <section id="featured">
        <div class="container">
            <h2>Popular on Tomato</h2>
            <div class="restaurant-grid">
                <?php include('../components/restaurantCard.php'); ?>
            </div>
        </div>
    </section>

    <section id="testimonials">
        <div class="container">
            <h2>What Our Customers Say</h2>
            <div class="testimonial-carousel">
                <div class="testimonial-slide">
                    <p>"Tomato's delivery is lightning fast! The food always arrives hot and fresh."</p>
                    <h4>Sarah M.</h4>
                </div>
                <div class="testimonial-slide">
                    <p>"Love the variety of restaurants! I can find anything I'm craving."</p>
                    <h4>Rahul K.</h4>
                </div>
                <div class="testimonial-slide">
                    <p>"The interface is so user-friendly, ordering is a breeze!"</p>
                    <h4>Emma L.</h4>
                </div>
            </div>
            <div class="carousel-controls">
                <button class="carousel-prev"><i class="fas fa-chevron-left"></i></button>
                <button class="carousel-next"><i class="fas fa-chevron-right"></i></button>
            </div>
        </div>
    </section>

    <section id="how-it-works">
        <div class="container">
            <h2>How Tomato Works</h2>
            <div class="steps">
                <div class="step">
                    <i class="fas fa-search fa-2x"></i>
                    <h3>1. Browse</h3>
                    <p>Find your favorite meals from top-rated restaurants.</p>
                </div>
                <div class="step">
                    <i class="fas fa-shopping-cart fa-2x"></i>
                    <h3>2. Order</h3>
                    <p>Place your order easily and pay online or cash on delivery.</p>
                </div>
                <div class="step">
                    <i class="fas fa-utensils fa-2x"></i>
                    <h3>3. Enjoy</h3>
                    <p>Your food arrives fresh and fast — enjoy every bite.</p>
                </div>
            </div>
        </div>
    </section>

    <button id="quick-order-btn" title="Quick Order">
        <i class="fas fa-utensils"></i>
    </button>

    <?php include('../components/footer.php'); ?>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/vanilla-tilt/1.7.0/vanilla-tilt.min.js"></script>
    <script>
        // Particle Animation (identical to index.php)
        document.addEventListener('DOMContentLoaded', () => {
            console.log('Particle script loaded');
            const canvas = document.getElementById('particle-canvas');
            if (!canvas) {
                console.error('Canvas element not found');
                return;
            }
            const ctx = canvas.getContext('2d');
            if (!ctx) {
                console.error('Canvas context not available');
                return;
            }

            canvas.width = window.innerWidth;
            canvas.height = window.innerHeight;

            window.addEventListener('resize', () => {
                canvas.width = window.innerWidth;
                canvas.height = window.innerHeight;
            });

            class Particle {
                constructor() {
                    this.x = Math.random() * canvas.width;
                    this.y = Math.random() * canvas.height;
                    this.z = Math.random() * 500;
                    this.size = Math.random() * 4 + 2;
                    this.speedX = (Math.random() - 0.5) * 3;
                    this.speedY = (Math.random() - 0.5) * 3;
                    const colors = [
                        `rgba(255, 87, 34, ${0.5 + Math.random() * 0.5})`,
                        `rgba(255, 125, 71, ${0.5 + Math.random() * 0.5})`,
                        `rgba(230, 74, 25, ${0.5 + Math.random() * 0.5})`
                    ];
                    this.color = colors[Math.floor(Math.random() * colors.length)];
                    this.shape = ['circle', 'square', 'star'][Math.floor(Math.random() * 3)];
                }

                update(mouseX, mouseY) {
                    this.x += this.speedX;
                    this.y += this.speedY;
                    const dx = this.x - mouseX;
                    const dy = this.y - mouseY;
                    const distance = Math.sqrt(dx * dx + dy * dy);
                    if (distance < 150) {
                        this.x += dx * 0.03;
                        this.y += dy * 0.03;
                    }
                    if (this.x < 0) this.x = canvas.width;
                    if (this.x > canvas.width) this.x = 0;
                    if (this.y < 0) this.y = canvas.height;
                    if (this.y > canvas.height) this.y = 0;
                }

                draw() {
                    const scale = 600 / (this.z + 600);
                    ctx.fillStyle = this.color;
                    ctx.beginPath();
                    if (this.shape === 'circle') {
                        ctx.arc(this.x, this.y, this.size * scale, 0, Math.PI * 2);
                        ctx.fill();
                    } else if (this.shape === 'square') {
                        ctx.fillRect(this.x - this.size * scale / 2, this.y - this.size * scale / 2, this.size * scale, this.size * scale);
                    } else if (this.shape === 'star') {
                        const points = 5;
                        const outer = this.size * scale;
                        const inner = outer / 2;
                        for (let i = 0; i < points * 2; i++) {
                            const r = i % 2 === 0 ? outer : inner;
                            const angle = (Math.PI / points) * i - Math.PI / 2;
                            ctx.lineTo(this.x + r * Math.cos(angle), this.y + r * Math.sin(angle));
                        }
                        ctx.closePath();
                        ctx.fill();
                    }
                }
            }

            const particles = [];
            for (let i = 0; i < 450; i++) {
                particles.push(new Particle());
            }

            let mouseX = canvas.width / 2;
            let mouseY = canvas.height / 2;

            document.addEventListener('mousemove', (e) => {
                mouseX = e.clientX;
                mouseY = e.clientY;
                const x = (e.clientX / window.innerWidth - 0.5) * 20;
                const y = (e.clientY / window.innerHeight - 0.5) * 20;
                document.body.style.setProperty('--bg-transform-before', `translateZ(-200px) rotateX(${y}deg) rotateY(${-x}deg)`);
                document.body.style.setProperty('--bg-transform-after', `translateZ(-100px) rotateX(${y / 2}deg) rotateY(${-x / 2}deg)`);
            });

            function animate() {
                ctx.globalAlpha = 0.8;
                ctx.clearRect(0, 0, canvas.width, canvas.height);
                particles.forEach(particle => {
                    particle.update(mouseX, mouseY);
                    particle.draw();
                });
                ctx.globalAlpha = 1.0;
                particles.forEach((particle, i) => {
                    for (let j = i + 1; j < particles.length; j++) {
                        const other = particles[j];
                        const dx = particle.x - other.x;
                        const dy = particle.y - other.y;
                        const distance = Math.sqrt(dx * dx + dy * dy);
                        if (distance < 100) {
                            ctx.beginPath();
                            ctx.moveTo(particle.x, particle.y);
                            ctx.lineTo(other.x, other.y);
                            ctx.strokeStyle = `rgba(255, 87, 34, ${1 - distance / 100})`;
                            ctx.lineWidth = 1;
                            ctx.stroke();
                        }
                    }
                });
                requestAnimationFrame(animate);
            }

            animate();

            // Interactive Features (identical to index.php)
            VanillaTilt.init(document.querySelectorAll(".category-card, .restaurant-card, .step, .testimonial-slide"), {
                max: 10,
                speed: 400,
                glare: true,
                "max-glare": 0.3
            });

            // Search Functionality
            const searchInput = document.getElementById('search-input');
            const searchSuggestions = document.getElementById('search-suggestions');
            const suggestions = ['Biryani', 'Pizza', 'Chinese', 'South Indian', 'Desserts', 'Fast Food', 'Tandoori'];

            searchInput.addEventListener('input', () => {
                const query = searchInput.value.toLowerCase();
                searchSuggestions.innerHTML = '';
                if (query) {
                    const filtered = suggestions.filter(item => item.toLowerCase().includes(query));
                    filtered.forEach(item => {
                        const div = document.createElement('div');
                        div.classList.add('suggestion-item');
                        div.textContent = item;
                        div.addEventListener('click', () => {
                            searchInput.value = item;
                            searchSuggestions.style.display = 'none';
                        });
                        searchSuggestions.appendChild(div);
                    });
                    searchSuggestions.style.display = filtered.length ? 'block' : 'none';
                } else {
                    searchSuggestions.style.display = 'none';
                }
            });

            // Testimonial Carousel
            const slides = document.querySelectorAll('.testimonial-slide');
            const prevBtn = document.querySelector('.carousel-prev');
            const nextBtn = document.querySelector('.carousel-next');
            let currentSlide = 0;

            function showSlide(index) {
                slides.forEach((slide, i) => {
                    slide.style.transform = `translateX(${(i - index) * 100}%)`;
                });
            }

            prevBtn.addEventListener('click', () => {
                currentSlide = (currentSlide - 1 + slides.length) % slides.length;
                showSlide(currentSlide);
            });

            nextBtn.addEventListener('click', () => {
                currentSlide = (currentSlide + 1) % slides.length;
                showSlide(currentSlide);
            });

            showSlide(currentSlide);

            // Countdown Timer
            const countdown = document.getElementById('countdown-timer');
            const endDate = new Date();
            endDate.setHours(endDate.getHours() + 24);

            function updateTimer() {
                const now = new Date();
                const timeLeft = endDate - now;
                if (timeLeft <= 0) {
                    countdown.textContent = 'Offer Expired!';
                    return;
                }
                const hours = Math.floor((timeLeft % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((timeLeft % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((timeLeft % (1000 * 60)) / 1000);
                countdown.textContent = `${hours}h ${minutes}m ${seconds}s`;
                setTimeout(updateTimer, 1000);
            }

            updateTimer();

            // Scroll Reveal Animation
            const revealElements = document.querySelectorAll('.container, .category-card, .restaurant-card, .step, .testimonial-slide');
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('reveal');
                        observer.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.1 });

            revealElements.forEach(el => observer.observe(el));

            // Quick Order Button
            const quickOrderBtn = document.getElementById('quick-order-btn');
            quickOrderBtn.addEventListener('click', () => {
                window.location.href = './restaurantList.php';
            });
        });
    </script>
    <script>
fetch('../backend/routes/restaurants.php')
  .then(response => response.json())
  .then(data => {
    const container = document.getElementById('restaurant-list');
    container.innerHTML = '';

    data.forEach(restaurant => {
      const div = document.createElement('div');
      div.innerHTML = `
        <h3>${restaurant.name}</h3>
        <p>${restaurant.description}</p>
        <p>Location: ${restaurant.location}</p>
        <a href="restaurantDetails.php?id=${restaurant.id}">View Menu</a>
        <hr>
      `;
      container.appendChild(div);
    });
  })
  .catch(error => console.error('Error fetching restaurants:', error));
</script>
</body>
</html>