<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - NutriGoal</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@300;400;500;600;700&family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: #F9F6F0;
        }

        :root {
            --primary: #5D9B6E;
            --primary-dark: #3D7A4E;
            --secondary: #F4A261;
            --secondary-dark: #E76F51;
            --white: #FFFFFF;
            --gray-light: #EDE9E2;
            --text-dark: #1E2A2E;
            --shadow-sm: 0 8px 20px rgba(0, 0, 0, 0.05);
            --shadow-md: 0 12px 28px rgba(0, 0, 0, 0.08);
            --radius-card: 20px;
        }

        /* Sidebar */
        .admin-container {
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 280px;
            background: #1E2A2E;
            color: white;
            padding: 30px 20px;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
        }

        .sidebar .logo {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 30px;
            text-align: center;
            color: var(--primary);
            text-decoration: none;
            display: block;
        }

        .sidebar .logo span {
            color: var(--secondary);
        }

        .sidebar nav a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            color: #B0C4B8;
            text-decoration: none;
            border-radius: 12px;
            margin-bottom: 8px;
            transition: all 0.2s;
        }

        .sidebar nav a:hover,
        .sidebar nav a.active {
            background: var(--primary);
            color: white;
        }

        .sidebar nav a i {
            width: 24px;
        }

        /* Main content */
        .main-content {
            flex: 1;
            margin-left: 280px;
            padding: 20px 30px;
        }

        /* Top bar */
        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 1px solid var(--gray-light);
        }

        .top-bar h1 {
            font-size: 1.5rem;
            font-family: 'Poppins', sans-serif;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .logout-btn {
            background: var(--secondary-dark);
            color: white;
            padding: 8px 16px;
            border-radius: 40px;
            text-decoration: none;
            font-size: 0.85rem;
        }

        /* Stats cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: var(--white);
            border-radius: var(--radius-card);
            padding: 20px;
            box-shadow: var(--shadow-sm);
            text-align: center;
        }

        .stat-card i {
            font-size: 2rem;
            color: var(--primary);
            margin-bottom: 10px;
        }

        .stat-card .number {
            font-size: 2rem;
            font-weight: 700;
        }

        .stat-card .label {
            color: #6B7A6F;
            font-size: 0.85rem;
        }

        /* Charts */
        .charts-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .chart-card {
            background: var(--white);
            border-radius: var(--radius-card);
            padding: 20px;
            box-shadow: var(--shadow-sm);
        }

        .chart-card h3 {
            margin-bottom: 20px;
            font-size: 1.1rem;
        }

        .chart-container {
            max-height: 300px;
        }

        /* Top regimes */
        .top-regimes {
            background: var(--white);
            border-radius: var(--radius-card);
            padding: 20px;
            box-shadow: var(--shadow-sm);
            margin-bottom: 30px;
        }

        .top-regimes h3 {
            margin-bottom: 20px;
        }

        .regime-list {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
        }

        .regime-item {
            flex: 1;
            background: #F9F6F0;
            border-radius: 16px;
            padding: 15px;
            text-align: center;
        }

        .regime-item .name {
            font-weight: 700;
            margin-bottom: 8px;
        }

        .regime-item .count {
            color: var(--primary);
            font-size: 1.5rem;
            font-weight: 700;
        }

        /* Recent users */
        .recent-users {
            background: var(--white);
            border-radius: var(--radius-card);
            padding: 20px;
            box-shadow: var(--shadow-sm);
        }

        .recent-users h3 {
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid var(--gray-light);
        }

        th {
            font-weight: 600;
            color: var(--primary);
        }

        .gold-badge {
            background: linear-gradient(135deg, #F4A261, #E76F51);
            color: white;
            padding: 2px 10px;
            border-radius: 20px;
            font-size: 0.7rem;
            font-weight: bold;
            display: inline-block;
        }

        .admin-badge {
            background: #1E2A2E;
            color: white;
            padding: 2px 10px;
            border-radius: 20px;
            font-size: 0.7rem;
            font-weight: bold;
            display: inline-block;
        }

        @media (max-width: 768px) {
            .sidebar {
                display: none;
            }

            .main-content {
                margin-left: 0;
            }

            .charts-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>
    <div class="admin-container">

        <!-- START MENU  -->
        <?= $this->include('partials/navbar') ?>
        <!-- END MENU  -->

        <!-- Main Content -->
        <div class="main-content">
            <div class="top-bar">
                <h1><i class="fas fa-tachometer-alt"></i> Tableau de bord</h1>
                <div class="user-info">
                    <span><i class="fas fa-user-shield"></i> <?= esc($admin_nom) ?></span>
                    <a href="/logout" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Déconnexion</a>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="stats-grid">
                <div class="stat-card">
                    <i class="fas fa-users"></i>
                    <div class="number"><?= $total_users ?></div>
                    <div class="label">Utilisateurs</div>
                </div>
                <div class="stat-card">
                    <i class="fas fa-crown"></i>
                    <div class="number"><?= $total_gold ?></div>
                    <div class="label">Membres Gold</div>
                </div>
                <div class="stat-card">
                    <i class="fas fa-bullseye"></i>
                    <div class="number"><?= $total_objectifs ?></div>
                    <div class="label">Objectifs actifs</div>
                </div>
                <div class="stat-card">
                    <i class="fas fa-utensils"></i>
                    <div class="number"><?= $total_regimes ?></div>
                    <div class="label">Régimes</div>
                </div>
            </div>

            <!-- Charts -->
            <div class="charts-grid">
                <div class="chart-card">
                    <h3><i class="fas fa-bullseye"></i> Objectifs en cours</h3>
                    <div class="chart-container">
                        <canvas id="objectifsChart"></canvas>
                    </div>
                </div>
                <div class="chart-card">
                    <h3><i class="fas fa-chart-pie"></i> Répartition IMC</h3>
                    <div class="chart-container">
                        <canvas id="imcChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Evolution inscriptions -->
            <div class="chart-card" style="margin-bottom: 30px;">
                <h3><i class="fas fa-chart-line"></i> Évolution des inscriptions (6 mois)</h3>
                <div class="chart-container">
                    <canvas id="evolutionChart"></canvas>
                </div>
            </div>

            <!-- Top 3 Régimes -->
            <div class="top-regimes">
                <h3><i class="fas fa-trophy"></i> Top 3 régimes les plus suggérés</h3>
                <div class="regime-list">
                    <?php if (!empty($top_regimes)): ?>
                        <?php foreach ($top_regimes as $regime): ?>
                            <div class="regime-item">
                                <div class="name"><?= esc($regime['nom_regime']) ?></div>
                                <div class="count"><?= $regime['count'] ?> suggestions</div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>Aucune suggestion pour le moment</p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Recent Users -->
            <div class="recent-users">
                <h3><i class="fas fa-user-plus"></i> Derniers inscrits</h3>
                <div style="overflow-x: auto;">
                    <table>
                        <thead>
                            <tr>
                                <th>Nom</th>
                                <th>Email</th>
                                <th>Genre</th>
                                <th>Date inscription</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($recent_users as $user): ?>
                                <tr>
                                    <td><?= esc($user['nom']) ?></td>
                                    <td><?= esc($user['email']) ?></td>
                                    <td><?= $user['genre'] === 'homme' ? '👨 Homme' : '👩 Femme' ?></td>
                                    <td><?= date('d/m/Y', strtotime($user['date_inscription'])) ?></td>
                                    <td>
                                        <?php if ($user['role'] === 'admin'): ?>
                                            <span class="admin-badge"><i class="fas fa-shield-alt"></i> Admin</span>
                                        <?php elseif ($user['is_gold']): ?>
                                            <span class="gold-badge"><i class="fas fa-crown"></i> Gold</span>
                                        <?php else: ?>
                                            <span style="color: #6B7A6F;">Standard</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Objectifs Chart (Donut)
        const ctx1 = document.getElementById('objectifsChart').getContext('2d');
        new Chart(ctx1, {
            type: 'doughnut',
            data: {
                labels: ['Prendre du poids', 'Perdre du poids', 'IMC Idéal'],
                datasets: [{
                    data: [<?= $objectifs_stats['augmenter_poids'] ?>, <?= $objectifs_stats['reduire_poids'] ?>, <?= $objectifs_stats['imc_ideal'] ?>],
                    backgroundColor: ['#5D9B6E', '#F4A261', '#E76F51'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });

        // IMC Chart (Barres)
        const ctx2 = document.getElementById('imcChart').getContext('2d');
        new Chart(ctx2, {
            type: 'bar',
            data: {
                labels: ['Dénutrition', 'Maigreur', 'Normal', 'Surpoids', 'Obésité modérée', 'Obésité sévère', 'Obésité massive'],
                datasets: [{
                    label: "Nombre d'utilisateurs",
                    data: [
                        <?= $repartition_imc['denutrition'] ?>,
                        <?= $repartition_imc['maigreur'] ?>,
                        <?= $repartition_imc['normal'] ?>,
                        <?= $repartition_imc['surpoids'] ?>,
                        <?= $repartition_imc['obesite_moderee'] ?>,
                        <?= $repartition_imc['obesite_severe'] ?>,
                        <?= $repartition_imc['obesite_massive'] ?>
                    ],
                    backgroundColor: '#5D9B6E',
                    borderRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });

        // Evolution Chart (Ligne)
        const ctx3 = document.getElementById('evolutionChart').getContext('2d');
        new Chart(ctx3, {
            type: 'line',
            data: {
                labels: <?= json_encode($evolution_inscriptions['mois']) ?>,
                datasets: [{
                    label: 'Nouveaux utilisateurs',
                    data: <?= json_encode($evolution_inscriptions['counts']) ?>,
                    borderColor: '#5D9B6E',
                    backgroundColor: 'rgba(93, 155, 110, 0.1)',
                    fill: true,
                    tension: 0.3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        position: 'top'
                    }
                }
            }
        });
    </script>
</body>

</html>