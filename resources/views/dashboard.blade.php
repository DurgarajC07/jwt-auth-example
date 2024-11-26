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
                const response = await axios.get('/api/dashboard-data', {
                    headers: {
                        Authorization: `Bearer ${token}`
                    }, // Pass token in the header
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
                        <th>Order Date</th>
                        <th>Customer</th>
                        <th>Quantity</th>
                        <th>Unit Price</th>
                        <th>Total Price</th>
                    </tr>
                </thead>
                <tbody>
        `;

                // Loop through each product
                products.forEach(product => {
                    // Display product data in the first row
                    tableHTML += `
                <tr>
                    <td colspan="6" class="text-center"><strong>${product.coffee_type} (${product.roast_type}) - ${product.size}</strong></td>
                </tr>
            `;

                    // If there are orders for the product
                    if (product.orders && product.orders.length > 0) {
                        // Loop through each order for the current product
                        product.orders.forEach(order => {
                            tableHTML += `
                        <tr>
                            <td>${product.coffee_type} (${product.roast_type})</td>
                            <td>${order.order_date}</td>
                            <td>${order.customer ? order.customer.customer_name : 'N/A'}</td>
                            <td>${order.quantity}</td>
                            <td>${order.unit_price}</td>
                            <td>${(order.quantity * order.unit_price).toFixed(2)}</td>
                        </tr>
                    `;
                        });
                    }
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

        // Call the function to load data
        fetchDashboardData();



        fetchUserDetails();
    </script>
</body>

</html>
