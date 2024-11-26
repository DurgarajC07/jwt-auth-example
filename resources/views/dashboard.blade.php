<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
</head>

<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-header bg-info text-white text-center">
                        <h4>Dashboard</h4>
                    </div>
                    <div class="card-body">
                        <div id="userDetails" class="text-center mb-3">
                            <p>Loading user details...</p>
                        </div>
                        <div class="text-center">
                            <button id="logoutButton" class="btn btn-danger">Logout</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="mb-3">
                    <input type="text" id="searchInput" class="form-control" placeholder="Search by product name..."
                        oninput="fetchDashboardData()">
                </div>
            </div>
        </div>
    </div>
    <div id="dashboardTable" class="container mt-4">
        <!-- Table will be injected here -->
    </div>

    <script>
        async function fetchUserDetails() {
            try {
                const token = localStorage.getItem('token');
                const response = await axios.get('/api/me', {
                    headers: {
                        Authorization: `Bearer ${token}`
                    },
                });
                document.getElementById('userDetails').innerHTML = `
                    <h5>Welcome, ${response.data.name}</h5>
                    <p>Email: ${response.data.email}</p>
                `;
            } catch (error) {
                alert('Session expired. Please log in again.');
                window.location.href = '/login';
            }
        }

        document.getElementById('logoutButton').addEventListener('click', async function() {
            try {
                const token = localStorage.getItem('token');
                await axios.post('/api/logout', {}, {
                    headers: {
                        Authorization: `Bearer ${token}`
                    },
                });
                localStorage.removeItem('token');
                alert('Logged out successfully!');
                window.location.href = '/';
            } catch (error) {
                alert('Logout failed.');
            }
        });
        async function fetchDashboardData() {
            try {
                const token = localStorage.getItem('token'); // Get token from local storage
                const searchQuery = document.getElementById('searchInput').value.trim(); // Get search query

                // Include search query as a parameter in the API request
                const response = await axios.get('/api/dashboard-data', {
                    headers: {
                        Authorization: `Bearer ${token}`
                    },
                    params: {
                        search: searchQuery // Pass the search query
                    }
                });

                const products = response.data; // Get the products data from the response

                console.log(products); // Log the data for debugging

                if (products.length === 0) {
                    document.getElementById('dashboardTable').innerHTML = `
                <p class="text-center">No data available.</p>
            `;
                    return;
                }

                let tableHTML = `
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Product Name</th>
                        <th>Roast Type</th>
                        <th>Size</th>
                        <th>Price per 100g</th>
                        <th>Unit Price</th>
                        <th>Profit</th>
                    </tr>
                </thead>
                <tbody>
        `;

                // Loop through each product
                products.forEach(product => {
                    // Display product data in the table row
                    tableHTML += `
                <tr>
                    <td>${product.coffee_type}</td>
                    <td>${product.roast_type}</td>
                    <td>${product.size}</td>
                    <td>${product.price_per_100g}</td>
                    <td>${product.unit_price}</td>
                    <td>${product.profit}</td>
                </tr>
            `;
                });

                tableHTML += `
                </tbody>
            </table>
        `;

                // Display the table in the designated div
                document.getElementById('dashboardTable').innerHTML = tableHTML;

            } catch (error) {
                console.error(error);
                document.getElementById('dashboardTable').innerHTML = `
            <p class="text-center text-danger">Failed to load data. Please try again.</p>
        `;
            }
        }

        // Initial call to fetch dashboard data
        fetchDashboardData();



        fetchUserDetails();
    </script>
</body>

</html>
