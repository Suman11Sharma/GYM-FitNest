<?php
header("Location: customerPage.php?status=error&msg=" . urlencode("❌ Payment failed or cancelled."));
exit;
