<?php require("../sidelayout.php"); ?>
<div id="layoutSidenav_content">
    <main>
        <?php
        $aboutCards = [
            [
                'id' => 1,
                'card' => 'Card 1',
                'heading' => 'Our Mission',
                'subtitle' => '"Strive for fitness and health"',
                'descriptions' => ['Affordable Memberships', 'Expert Trainers', 'Flexible Timing']
            ],
            [
                'id' => 2,
                'card' => 'Card 2',
                'heading' => 'Our Vision',
                'subtitle' => '"A fit nation is a strong nation"',
                'descriptions' => ['Nationwide Expansion', 'Empower Youth']
            ],
            [
                'id' => 3,
                'card' => 'Card 3',
                'heading' => 'What Makes Us Unique',
                'subtitle' => '"Innovation meets dedication"',
                'descriptions' => ['Personalized Plans', 'Smart Tech', '24/7 Support']
            ]
        ];
        ?>

        <?php include '../Layouts/header.php'; ?>
        <div class="container mt-4">
            <h2 class="mb-4">About Us Cards</h2>

            <div class="d-flex justify-content-between mb-3">
                <a href="create.php" class="btn btn-primary">Add Card Info</a>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-hover shadow-sm">
                    <thead class="table-dark">
                        <tr>
                            <th>Card</th>
                            <th>Heading Title</th>
                            <th>Quote</th>
                            <th>Description Points</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($aboutCards as $card): ?>
                            <tr>
                                <td><?= htmlspecialchars($card['card']) ?></td>
                                <td><?= htmlspecialchars($card['heading']) ?></td>
                                <td><em><?= htmlspecialchars($card['subtitle']) ?></em></td>
                                <td>
                                    <ul class="mb-0 ps-3">
                                        <?php foreach ($card['descriptions'] as $point): ?>
                                            <li><?= htmlspecialchars($point) ?></li>
                                        <?php endforeach; ?>
                                    </ul>
                                </td>
                                <td>
                                    <a href="edit.php?id=<?= $card['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                                    <a href="delete.php?id=<?= $card['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if (empty($aboutCards)): ?>
                            <tr>
                                <td colspan="5" class="text-center">No data available.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
    <?php require("../assets/link.php"); ?>