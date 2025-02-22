<?php
require 'database_v2.php';

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

$input = json_decode(file_get_contents('php://input'), true);
$action = $input['action'] ?? $_GET['action'];


try {
    switch($action) {
case 'addNote':
    $stmt = $conn->prepare("INSERT INTO notes_v2 
        (groupName, responsible, projectName, topic, description, date) 
        VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", 
    $input['groupName'],
    $input['responsible'],
    $input['projectName'],
    $input['topic'],
    $input['description'],
    $input['date']
    );
    $stmt->execute();
    $newId = $stmt->insert_id; // Burada yeni ID'yi al
    echo json_encode(['success' => true, 'id' => $newId]); // ID'yi dön
    break;
            
        case 'getNotes':
            $notes = $conn->query("SELECT * FROM notes_v2")->fetch_all(MYSQLI_ASSOC);
            echo json_encode($notes);
            break;

case 'getNote':
    $stmt = $conn->prepare("SELECT * FROM notes_v2 WHERE id = ?");
    $stmt->bind_param("i", $_POST['id']);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    echo json_encode(['success' => !!$result, 'data' => $result]);
    break;

    case 'deleteNote':
        $stmt = $conn->prepare("DELETE FROM notes_v2 WHERE id = ?");
        $stmt->bind_param("i", $input['id']);
        $stmt->execute(); // EKLENECEK
        echo json_encode(['success' => $stmt->affected_rows > 0]); // EKLENECEK
        break;

        case 'updateNote':
            $stmt = $conn->prepare("UPDATE notes_v2 SET 
            groupName = ?, 
            responsible = ?, 
            projectName = ?, 
            topic = ?, 
            date = ?, 
            description = ?, 
            changeDate = ?, 
            completed = ? 
            WHERE id = ?");
        $stmt->bind_param("sssssssii",
            $input['groupName'],
            $input['responsible'],
            $input['projectName'],
            $input['topic'],
            $input['date'],
            $input['description'],
            $input['changeDate'],
            $input['completed'],
            $input['id']
        );
            $stmt->execute();
            echo json_encode(['success' => true]);
            break;

            case 'importNotes':
                // Gelen notları döngüyle tek tek ekle
                foreach ($input['notes'] as $note) {
                    $stmt = $conn->prepare("INSERT INTO notes_v2
                    (groupName, responsible, projectName, topic, description, date, changeDate, completed)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    
                    $stmt->bind_param(
                        "sssssssi",  // Tip belirteçleri (completed için 'i')
                        $note['groupName'],
                        $note['responsible'],
                        $note['projectName'],
                        $note['topic'],
                        $note['description'],
                        $note['date'],
                        $note['changeDate'],
                        $note['completed'] // Tamamlanma durumunu da ekle
                    );
                    $stmt->execute();
                }
                echo json_encode(['success' => true]);
                break;

        default:
            throw new Exception("Geçersiz işlem");
    }
} catch(Exception $e) {
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
?>