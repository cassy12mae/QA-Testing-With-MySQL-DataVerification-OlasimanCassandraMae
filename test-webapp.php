
<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "birthday_wishnote";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

$success = false;
$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $firstName = $_POST["FirstName"] ?? "";
    $lastName = $_POST["LastName"] ?? "";
    $govPosition = $_POST["GovPosition"] ?? "";
    $location = $_POST["Location"] ?? "";

    if (
        trim($firstName) === "" ||
        trim($lastName) === "" ||
        trim($govPosition) === ""
    ) {
        $error = "Please complete all required fields.";
    } else {
        /*
         * Intentional assignment flaw:
         * Location is captured from the form but is omitted from
         * the INSERT statement and bind_param().
         */
        $stmt = $conn->prepare(
            "INSERT INTO main_corrupt_politicians
            (FirstName, LastName, GovPosition)
            VALUES (?, ?, ?)"
        );

        if ($stmt) {
            $stmt->bind_param(
                "sss",
                $firstName,
                $lastName,
                $govPosition
            );

            if ($stmt->execute()) {
                $success = true;
            } else {
                $error = "Unable to save the wish note.";
            }

            $stmt->close();
        } else {
            $error = "Unable to prepare the database query.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Kara David's Wish Note</title>
<style>
* { box-sizing: border-box; }
body {
    margin: 0;
    padding: 24px;
    font-family: Arial, sans-serif;
    background: #f1f5f9;
    color: #1e293b;
}
.card {
    max-width: 560px;
    margin: 35px auto;
    padding: 30px;
    background: white;
    border-radius: 14px;
    box-shadow: 0 8px 28px rgba(15, 23, 42, .12);
}
h2 { color: #2563a6; margin-top: 0; }
label { display: block; margin: 16px 0 7px; font-weight: bold; }
input {
    width: 100%;
    padding: 12px;
    border: 1px solid #cbd5e1;
    border-radius: 7px;
    font-size: 16px;
}
button {
    padding: 12px 16px;
    border: 0;
    border-radius: 7px;
    background: #2563a6;
    color: white;
    font-size: 15px;
    cursor: pointer;
}
button:hover { background: #174779; }
.submit { width: 100%; margin-top: 22px; }
.message { padding: 12px; border-radius: 7px; margin-bottom: 18px; }
.success { background: #dcfce7; color: #166534; }
.error { background: #fee2e2; color: #991b1b; }
.overlay {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(15, 23, 42, .6);
    align-items: center;
    justify-content: center;
    padding: 20px;
}
.modal {
    background: white;
    width: 100%;
    max-width: 460px;
    padding: 25px;
    border-radius: 12px;
    box-shadow: 0 12px 35px rgba(0,0,0,.2);
}
.modal p { overflow-wrap: anywhere; }
.actions { display: flex; justify-content: flex-end; gap: 10px; margin-top: 20px; }
.cancel { background: #64748b; }
@media (max-width: 480px) {
    body { padding: 10px; }
    .card { padding: 20px; margin: 12px auto; }
}
</style>
</head>
<body>
<div class="card">
    <h2>Kara David's Wish Note</h2>

    <?php if ($success): ?>
        <div class="message success">Success! The wish note has been sent.</div>
    <?php endif; ?>

    <?php if ($error !== ""): ?>
        <div class="message error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form id="wishForm" method="POST">
        <label for="FirstName">Target's First Name</label>
        <input id="FirstName" name="FirstName" maxlength="30" required>

        <label for="LastName">Target's Last Name</label>
        <input id="LastName" name="LastName" maxlength="30" required>

        <label for="GovPosition">Target's Government Position</label>
        <input id="GovPosition" name="GovPosition" maxlength="20" required>

        <label for="Location">Target's Location</label>
        <input id="Location" name="Location" maxlength="40">

        <button class="submit" type="submit">Send Wish Note</button>
    </form>
</div>

<div class="overlay" id="overlay" role="dialog" aria-modal="true">
    <div class="modal">
        <h3>Verify Target Data</h3>
        <div id="preview"></div>
        <div class="actions">
            <button type="button" class="cancel" id="cancelBtn">Cancel</button>
            <button type="button" id="confirmBtn">Confirm &amp; Send</button>
        </div>
    </div>
</div>

<script>
const form = document.getElementById("wishForm");
const overlay = document.getElementById("overlay");
const preview = document.getElementById("preview");

form.addEventListener("submit", function (e) {
    e.preventDefault();

    const fields = [
        ["FirstName", "First Name"],
        ["LastName", "Last Name"],
        ["GovPosition", "Government Position"],
        ["Location", "Location"]
    ];

    preview.replaceChildren();

    fields.forEach(function ([name, label]) {
        const p = document.createElement("p");
        const strong = document.createElement("strong");
        strong.textContent = label + ": ";
        p.appendChild(strong);
        p.appendChild(document.createTextNode(
            document.getElementById(name).value || "(blank)"
        ));
        preview.appendChild(p);
    });

    overlay.style.display = "flex";
});

document.getElementById("cancelBtn").addEventListener("click", function () {
    overlay.style.display = "none";
});

document.getElementById("confirmBtn").addEventListener("click", function () {
    form.submit();
});
</script>
</body>
</html>
<?php $conn->close(); ?>
