<?php include '../components/navbar.php'; ?>
<div class="container">
  <h2>Find Restaurants Near You</h2>
  <form id="locationForm">
    <input type="text" id="locationInput" placeholder="Enter your location" required>
    <button type="submit">Search</button>
  </form>
  <div id="restaurantResults" class="restaurant-grid"></div>
</div>
<?php include '../components/footer.php'; ?>

<script>
  document.getElementById('locationForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    const location = document.getElementById('locationInput').value;
    const geoKey = 'd8e028487cc24a55ad0239ae27069264';
    const fourKey = 'fsq3ylDv92qHejK+uR8Bi34nfrB9tXXMyYTI7HGR4reekJg=';

    const geoRes = await fetch(`https://api.opencagedata.com/geocode/v1/json?q=${location}&key=${geoKey}`);
    const geoData = await geoRes.json();
    const { lat, lng } = geoData.results[0].geometry;

    const fsqRes = await fetch(`https://api.foursquare.com/v3/places/search?ll=${lat},${lng}&categories=13065&limit=10`, {
      headers: {
        Accept: 'application/json',
        Authorization: fourKey
      }
    });
    const fsqData = await fsqRes.json();
    const container = document.getElementById('restaurantResults');
    container.innerHTML = '';
    fsqData.results.forEach(place => {
      container.innerHTML += `
        <div class="restaurant-card">
          <h3>${place.name}</h3>
          <p>${place.location.address || 'No address available'}</p>
          <a href="restaurantDetails.php?id=${place.fsq_id}&name=${encodeURIComponent(place.name)}">View Menu</a>
        </div>`;
    });
  });
</script>
<style>
  /* Main theme color and variables */
:root {
  --primary: #c0523a;
  --primary-light: #e07963;
  --primary-dark: #a03020;
  --primary-transparent: rgba(192, 82, 58, 0.1);
  --text-dark: #333333;
  --text-light: #ffffff;
  --bg-light: #f9f5f3;
  --border-radius: 8px;
  --box-shadow: 0 4px 12px rgba(192, 82, 58, 0.15);
}

/* Base styles */
body {
  font-family: 'Poppins', sans-serif;
  background-color: var(--bg-light);
  color: var(--text-dark);
  line-height: 1.6;
  margin: 0;
  padding: 0;
}

/* Container - Modified to ensure column layout */
.container {
  max-width: 1200px;
  margin: 2rem auto;
  padding: 0 1.5rem;
  display: flex;
  flex-direction: column;
}

/* Navbar styling */
.navbar {
  background-color: var(--primary);
  padding: 1rem 0;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.navbar .container {
  display: flex;
  flex-direction: row; /* Override the column direction for navbar only */
  justify-content: space-between;
  align-items: center;
  margin: 0 auto;
}

.navbar-brand {
  color: var(--text-light);
  font-size: 1.8rem;
  font-weight: 700;
  text-decoration: none;
}

.navbar-nav {
  display: flex;
  list-style: none;
  gap: 1.5rem;
  margin: 0;
  padding: 0;
}

.navbar-nav a {
  color: var(--text-light);
  text-decoration: none;
  font-weight: 500;
  transition: opacity 0.3s;
}

.navbar-nav a:hover {
  opacity: 0.8;
}

/* Header styling */
h2 {
  color: var(--primary-dark);
  text-align: center;
  font-size: 2.5rem;
  margin-bottom: 2rem;
  position: relative;
  padding-bottom: 15px;
  width: 100%;
}

h2::after {
  content: '';
  position: absolute;
  bottom: 0;
  left: 50%;
  transform: translateX(-50%);
  width: 80px;
  height: 3px;
  background-color: var(--primary);
}

/* Location form styling */
#locationForm {
  width: 100%;
  max-width: 600px;
  margin: 0 auto 3rem;
  display: flex;
  gap: 1rem;
  position: relative;
}

#locationInput {
  flex-grow: 1;
  padding: 1rem 1.2rem;
  border: 2px solid #e0e0e0;
  border-radius: var(--border-radius);
  font-size: 1rem;
  transition: border-color 0.3s;
}

#locationInput:focus {
  outline: none;
  border-color: var(--primary);
}

#locationForm button {
  background-color: var(--primary);
  color: white;
  border: none;
  padding: 1rem 1.5rem;
  border-radius: var(--border-radius);
  font-weight: 600;
  cursor: pointer;
  transition: background-color 0.3s;
}

#locationForm button:hover {
  background-color: var(--primary-dark);
}

/* Restaurant grid styling */
.restaurant-grid {
  width: 100%;
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 1.5rem;
  margin-top: 2rem;
}

/* Restaurant card styling */
.restaurant-card {
  background-color: white;
  border-radius: var(--border-radius);
  padding: 1.5rem;
  box-shadow: var(--box-shadow);
  transition: transform 0.3s;
}

.restaurant-card:hover {
  transform: translateY(-5px);
}

.restaurant-card h3 {
  color: var(--primary-dark);
  margin-top: 0;
  font-size: 1.3rem;
}

.restaurant-card p {
  color: #666;
  margin-bottom: 1.5rem;
}

.restaurant-card a {
  display: inline-block;
  background-color: var(--primary);
  color: white;
  padding: 0.6rem 1.2rem;
  text-decoration: none;
  border-radius: 4px;
  font-weight: 500;
  transition: background-color 0.3s;
}

.restaurant-card a:hover {
  background-color: var(--primary-dark);
}

/* Loading indicator */
.loading {
  text-align: center;
  padding: 2rem;
  color: var(--primary);
  font-size: 1.2rem;
}

/* Footer styling */
footer {
  background-color: var(--primary-dark);
  color: var(--text-light);
  padding: 2rem 0;
  margin-top: 3rem;
}

.footer-content {
  display: flex;
  justify-content: space-between;
  flex-wrap: wrap;
  gap: 2rem;
}

.footer-section {
  flex: 1;
  min-width: 250px;
}

.footer-section h3 {
  font-size: 1.3rem;
  margin-bottom: 1rem;
  position: relative;
  padding-bottom: 10px;
}

.footer-section h3::after {
  content: '';
  position: absolute;
  left: 0;
  bottom: 0;
  width: 50px;
  height: 2px;
  background-color: var(--primary-light);
}

.footer-section p, .footer-section a {
  color: rgba(255, 255, 255, 0.8);
  margin-bottom: 0.5rem;
  display: block;
  text-decoration: none;
}

.footer-section a:hover {
  color: white;
}

.copyright {
  text-align: center;
  padding-top: 2rem;
  margin-top: 2rem;
  border-top: 1px solid rgba(255, 255, 255, 0.1);
}

/* Responsive adjustments */
@media (max-width: 992px) {
  .restaurant-grid {
    grid-template-columns: repeat(2, 1fr);
  }
}

@media (max-width: 768px) {
  #locationForm {
    flex-direction: column;
  }
  
  h2 {
    font-size: 2rem;
  }
}

@media (max-width: 576px) {
  .restaurant-grid {
    grid-template-columns: 1fr;
  }
}
</style>