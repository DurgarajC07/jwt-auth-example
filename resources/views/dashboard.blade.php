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

        fetchUserDetails();
    </script>
</body>

</html>
