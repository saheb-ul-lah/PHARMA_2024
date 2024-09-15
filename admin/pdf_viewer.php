<?php
include('includes/db_connect.php'); // Include the database connection

// Fetch journals and their sections
$sql = "
SELECT j.id AS journal_id, j.journal_name, j.pdf, ps.section_name, ps.start_page, ps.end_page, ps.profile_name, ps.profile_image 
FROM pharma_journals j 
LEFT JOIN pharma_sections ps ON j.id = ps.journal_id 
WHERE j.status = 'active'
ORDER BY j.id, ps.start_page
";
$result = $conn->query($sql);

$journals = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $journals[$row['journal_id']]['journal_name'] = $row['journal_name'];
        $journals[$row['journal_id']]['pdf'] = $row['pdf'];
        $journals[$row['journal_id']]['sections'][] = [
            'section_name' => $row['section_name'],
            'start_page' => $row['start_page'],
            'end_page' => $row['end_page'],
            'profile_name' => $row['profile_name'],
            'profile_image' => $row['profile_image']
        ];
    }
}
$conn->close();
?>
<?php include('includes/header.php'); ?>
<div class="pdf-container">
                <div class="pdf-sidebar">
                    <ul class="list-group">
                        <?php foreach ($journals as $journal_id => $journal): ?>
                            <li class="list-group-item">
                                <h5><?= $journal['journal_name'] ?></h5>
                                <ul>
                                    <?php foreach ($journal['sections'] as $section): ?>
                                        <li>
                                            <a href="#" data-journal="<?= $journal['pdf'] ?>" data-start="<?= $section['start_page'] ?>" data-end="<?= $section['end_page'] ?>">
                                                <?= $section['section_name'] ?>
                                            </a>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <div class="pdf-viewer">
                    <iframe id="pdf-frame" width="100%" height="100%"></iframe>
                </div>
</div>
<?php include('includes/footer.php'); ?>
