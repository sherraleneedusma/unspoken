<?php
include 'db.php';

$search = isset($_GET['search']) ? trim($_GET['search']) : '';

// Prepare SQL with search filter and limit 20 latest messages
if ($search !== '') {
    $stmt = $conn->prepare("SELECT * FROM letters WHERE recipient LIKE CONCAT('%', ?, '%') ORDER BY created_at DESC LIMIT 20");
    $stmt->bind_param("s", $search);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $sql = "SELECT * FROM letters ORDER BY created_at DESC LIMIT 20";
    $result = $conn->query($sql);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Unspoken</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .letter-card {
            background: white;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        }
        .letter-recipient {
            font-weight: bold;
            color: #6c757d;
        }
        .letter-message {
            margin-top: 10px;
            white-space: pre-wrap;
        }
        header {
            padding: 40px 0;
            text-align: center;
            background-color: #343a40;
            color: white;
            margin-bottom: 40px;
        }
        footer {
            padding: 20px 0;
            text-align: center;
            background-color: #343a40;
            color: white;
            margin-top: 40px;
        }
    </style>
</head>
<body>

<header>
    <h1>Unspoken</h1>
    <p class="fst-italic" style="color: #e0e0e0;">Whispers of the heart, words left unspoken.</p>
</header>

<div class="container">
    <form method="GET" class="mb-4 d-flex" role="search" aria-label="Search letters by recipient name">
        <input class="form-control me-2" type="search" name="search" placeholder="Search by recipient name" aria-label="Search" value="<?php echo htmlspecialchars($search); ?>">
        <button class="btn btn-outline-primary" type="submit">Search</button>
    </form>

    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success" role="alert">
            <?php echo htmlspecialchars($_GET['success']); ?>
        </div>
    <?php elseif (isset($_GET['error'])): ?>
        <div class="alert alert-danger" role="alert">
            <?php echo htmlspecialchars($_GET['error']); ?>
        </div>
    <?php endif; ?>

    <?php if ($result && $result->num_rows > 0): ?>
        <div class="row row-cols-1 row-cols-md-4 g-4">
        <?php while($row = $result->fetch_assoc()): ?>
            <div class="col">
                <div class="letter-card h-100">
                    <?php if (!empty($row['recipient'])): ?>
                        <div class="letter-recipient">To: <?php echo htmlspecialchars($row['recipient']); ?></div>
                    <?php endif; ?>
                    <div class="letter-message"><?php echo nl2br(htmlspecialchars($row['message'])); ?></div>
                    <div class="text-muted" style="font-size: 0.8em; margin-top: 10px;">
                        <?php echo date('F j, Y, g:i a', strtotime($row['created_at'])); ?>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
        </div>
    <?php else: ?>
        <p>No letters found.</p>
    <?php endif; ?>

    <hr>

    <button id="showFormBtn" class="btn btn-primary mb-3">Submit a Message</button>

    <div id="letterForm" style="display:none;">
        <h2>Submit a New Letter</h2>
        <form action="submit_letter.php" method="POST" class="mb-5">
            <div class="mb-3">
                <label for="recipient" class="form-label">To (optional)</label>
                <input type="text" class="form-control" id="recipient" name="recipient" maxlength="255" placeholder="Recipient's name">
            </div>
            <div class="mb-3">
                <label for="message" class="form-label">Letter</label>
                <textarea class="form-control" id="message" name="message" rows="5" required placeholder="Write your unsent letter here..."></textarea>
            </div>
            <div class="mb-3 text-danger">
                <small>After the message is submitted, you can't delete it.</small>
            </div>
            <button type="submit" class="btn btn-primary me-2">Submit Letter</button>
            <button type="reset" class="btn btn-secondary me-2">Clear</button>
            <button type="button" class="btn btn-outline-secondary" onclick="history.back()">Back</button>
        </form>
    </div>
</div>

<script>
    document.getElementById('showFormBtn').addEventListener('click', function() {
        var formDiv = document.getElementById('letterForm');
        if (formDiv.style.display === 'none') {
            formDiv.style.display = 'block';
            this.style.display = 'none';
        }
    });
</script>

<footer>
    &copy; <?php echo date('Y'); ?> Unspoken
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
