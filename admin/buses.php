<?php
session_start();
require_once '../config/database.php';

// Check if user is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

$action = $_GET['action'] ?? 'list';
$message = '';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($action === 'new' || $action === 'edit') {
        $bus_name = trim($_POST['bus_name'] ?? '');
        $model = trim($_POST['model'] ?? '');
        $year = (int)($_POST['year'] ?? '');
        $capacity = (int)($_POST['capacity'] ?? '');
        $price = (float)($_POST['price'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $features = trim($_POST['features'] ?? '');
        $status = $_POST['status'] ?? 'available';
        
        if (empty($bus_name) || empty($model) || $year <= 0 || $capacity <= 0 || $price <= 0) {
            $message = 'Please fill in all required fields with valid values';
        } else {
            try {
                $pdo = getDBConnection();
                
                if ($action === 'new') {
                    $stmt = $pdo->prepare("INSERT INTO buses (bus_name, model, year, capacity, price, description, features, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                    $result = $stmt->execute([$bus_name, $model, $year, $capacity, $price, $description, $features, $status]);
                    if ($result) {
                        $message = 'Bus added successfully';
                        $action = 'list';
                    }
                } else {
                    $id = $_POST['id'];
                    $stmt = $pdo->prepare("UPDATE buses SET bus_name = ?, model = ?, year = ?, capacity = ?, price = ?, description = ?, features = ?, status = ? WHERE id = ?");
                    $result = $stmt->execute([$bus_name, $model, $year, $capacity, $price, $description, $features, $status, $id]);
                    if ($result) {
                        $message = 'Bus updated successfully';
                        $action = 'list';
                    }
                }
            } catch (Exception $e) {
                $message = 'Error: ' . $e->getMessage();
            }
        }
    } elseif ($action === 'delete') {
        $id = $_POST['id'] ?? 0;
        if ($id) {
            try {
                $pdo = getDBConnection();
                $stmt = $pdo->prepare("DELETE FROM buses WHERE id = ?");
                $result = $stmt->execute([$id]);
                if ($result) {
                    $message = 'Bus deleted successfully';
                }
            } catch (Exception $e) {
                $message = 'Error: ' . $e->getMessage();
            }
        }
        $action = 'list';
    }
}

// Get buses for listing
function getBuses() {
    try {
        $pdo = getDBConnection();
        $stmt = $pdo->query("SELECT * FROM buses ORDER BY created_at DESC");
        return $stmt->fetchAll();
    } catch (Exception $e) {
        return [];
    }
}

// Get single bus for editing
function getBus($id) {
    try {
        $pdo = getDBConnection();
        $stmt = $pdo->prepare("SELECT * FROM buses WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    } catch (Exception $e) {
        return null;
    }
}

$buses = getBuses();
$currentBus = null;

if ($action === 'edit' && isset($_GET['id'])) {
    $currentBus = getBus($_GET['id']);
    if (!$currentBus) {
        $action = 'list';
        $message = 'Bus not found';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buses Management - Annhurst Transport</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#b5121b',
                        'primary-dark': '#8f0e15'
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-100">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="flex items-center">
                            <div class="rounded-lg flex items-center justify-center mr-3">
                                <img src="../img/ann.png" alt="Annhurst Transport" class="h-10 w-auto">
                            </div>
                            <span class="text-xl font-bold text-gray-900">Admin Panel</span>
                        </div>
                    </div>
                </div>
                
                <div class="flex items-center space-x-4">
                    <span class="text-gray-700">Welcome, <?php echo htmlspecialchars($_SESSION['admin_name']); ?></span>
                    <a href="logout.php" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors">Logout</a>
                </div>
            </div>
        </div>
    </nav>

    <div class="flex">
        <!-- Sidebar -->
        <div class="w-64 bg-white shadow-lg min-h-screen">
            <div class="p-4">
                <nav class="space-y-2">
                    <a href="dashboard.php" class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-md">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5v14m8-14v14"></path>
                        </svg>
                        Dashboard
                    </a>
                    <a href="pages.php" class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-md">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Pages
                    </a>
                    <a href="buses.php" class="flex items-center px-4 py-2 text-gray-700 bg-primary text-white rounded-md">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        Buses
                    </a>
                    <a href="contacts.php" class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-md">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        Contact Messages
                    </a>
                    <a href="settings.php" class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-md">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        Settings
                    </a>
                    <a href="../index.php" class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-md">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                        </svg>
                        View Website
                    </a>
                </nav>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 p-8">
            <div class="mb-8 flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Buses Management</h1>
                    <p class="text-gray-600">Add and manage bus listings</p>
                </div>
                <?php if ($action === 'list'): ?>
                    <a href="?action=new" class="bg-primary hover:bg-primary-dark text-white px-4 py-2 rounded-md text-sm font-medium transition-colors">
                        Add New Bus
                    </a>
                <?php endif; ?>
            </div>

            <?php if ($message): ?>
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    <?php echo htmlspecialchars($message); ?>
                </div>
            <?php endif; ?>

            <?php if ($action === 'list'): ?>
                <!-- Buses List -->
                <div class="bg-white shadow rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">All Buses</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bus Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Model</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Year</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Capacity</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php foreach ($buses as $bus): ?>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900"><?php echo htmlspecialchars($bus['bus_name']); ?></div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-500"><?php echo htmlspecialchars($bus['model']); ?></div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <?php echo $bus['year']; ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <?php echo $bus['capacity']; ?> passengers
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        ₦<?php echo number_format($bus['price']); ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            <?php echo $bus['status'] === 'available' ? 'bg-green-100 text-green-800' : 
                                                  ($bus['status'] === 'sold' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800'); ?>">
                                            <?php echo ucfirst($bus['status']); ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="?action=edit&id=<?php echo $bus['id']; ?>" class="text-primary hover:text-primary-dark mr-3">Edit</a>
                                        <form method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this bus?')">
                                            <input type="hidden" name="id" value="<?php echo $bus['id']; ?>">
                                            <button type="submit" name="action" value="delete" class="text-red-600 hover:text-red-900">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

            <?php elseif ($action === 'new' || $action === 'edit'): ?>
                <!-- Bus Form -->
                <div class="bg-white shadow rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-6">
                        <?php echo $action === 'new' ? 'Add New Bus' : 'Edit Bus'; ?>
                    </h3>
                    
                    <form method="POST" class="space-y-6">
                        <?php if ($action === 'edit'): ?>
                            <input type="hidden" name="id" value="<?php echo $currentBus['id']; ?>">
                        <?php endif; ?>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="bus_name" class="block text-sm font-medium text-gray-700">Bus Name *</label>
                                <input type="text" id="bus_name" name="bus_name" required 
                                       value="<?php echo htmlspecialchars($currentBus['bus_name'] ?? ''); ?>"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary focus:border-primary sm:text-sm">
                            </div>
                            
                            <div>
                                <label for="model" class="block text-sm font-medium text-gray-700">Model *</label>
                                <input type="text" id="model" name="model" required 
                                       value="<?php echo htmlspecialchars($currentBus['model'] ?? ''); ?>"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary focus:border-primary sm:text-sm">
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label for="year" class="block text-sm font-medium text-gray-700">Year *</label>
                                <input type="number" id="year" name="year" required min="1900" max="2030"
                                       value="<?php echo $currentBus['year'] ?? date('Y'); ?>"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary focus:border-primary sm:text-sm">
                            </div>
                            
                            <div>
                                <label for="capacity" class="block text-sm font-medium text-gray-700">Passenger Capacity *</label>
                                <input type="number" id="capacity" name="capacity" required min="1" max="100"
                                       value="<?php echo $currentBus['capacity'] ?? ''; ?>"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary focus:border-primary sm:text-sm">
                            </div>
                            
                            <div>
                                <label for="price" class="block text-sm font-medium text-gray-700">Price (₦) *</label>
                                <input type="number" id="price" name="price" required min="0" step="0.01"
                                       value="<?php echo $currentBus['price'] ?? ''; ?>"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary focus:border-primary sm:text-sm">
                            </div>
                        </div>
                        
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700">Description *</label>
                            <textarea id="description" name="description" rows="4" required
                                      class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary focus:border-primary sm:text-sm"><?php echo htmlspecialchars($currentBus['description'] ?? ''); ?></textarea>
                        </div>
                        
                        <div>
                            <label for="features" class="block text-sm font-medium text-gray-700">Features</label>
                            <textarea id="features" name="features" rows="3"
                                      class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary focus:border-primary sm:text-sm"><?php echo htmlspecialchars($currentBus['features'] ?? ''); ?></textarea>
                            <p class="mt-1 text-sm text-gray-500">List the bus features (e.g., Air conditioning, WiFi, USB charging)</p>
                        </div>
                        
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                            <select id="status" name="status" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary focus:border-primary sm:text-sm">
                                <option value="available" <?php echo ($currentBus['status'] ?? '') === 'available' ? 'selected' : ''; ?>>Available</option>
                                <option value="reserved" <?php echo ($currentBus['status'] ?? '') === 'reserved' ? 'selected' : ''; ?>>Reserved</option>
                                <option value="sold" <?php echo ($currentBus['status'] ?? '') === 'sold' ? 'selected' : ''; ?>>Sold</option>
                            </select>
                        </div>
                        
                        <div class="flex justify-end space-x-3">
                            <a href="?action=list" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-md text-sm font-medium transition-colors">
                                Cancel
                            </a>
                            <button type="submit" class="bg-primary hover:bg-primary-dark text-white px-4 py-2 rounded-md text-sm font-medium transition-colors">
                                <?php echo $action === 'new' ? 'Add Bus' : 'Update Bus'; ?>
                            </button>
                        </div>
                    </form>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
