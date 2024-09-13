<?php

include 'config.php'; // Ensure this file initializes $conn correctly

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'delete_agent':
                if (isset($_POST['id'])) {
                    $id = intval($_POST['id']); // Use intval to sanitize the ID input
                
                    // Start transaction
                    $conn->begin_transaction();
                
                    try {
                        // Prepare the second statement to update the users table
                        $stmt2 = $conn->prepare("UPDATE users SET status = 'inactive', deleted_at = NOW() WHERE id = ?");
                        if ($stmt2) {
                            $stmt2->bind_param("i", $id);
                            if (!$stmt2->execute()) {
                                throw new Exception("Error executing the query: " . $stmt2->error);
                            }
                            $stmt2->close();
                        } else {
                            throw new Exception("Error preparing the SQL statement: " . $conn->error);
                        }
                
                        // Commit transaction
                        $conn->commit();
                
                        echo json_encode(['status' => 'success', 'message' => "Records deleted successfully."]);
                    } catch (Exception $e) {
                        // Rollback transaction on error
                        $conn->rollback();
                        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
                    }
                }

                break;

            case 'restore_agent':
                if (isset($_POST['id'])) {
                    $id = intval($_POST['id']); // Use intval to sanitize the ID input
                
                    // Start transaction
                    $conn->begin_transaction();
                
                    try {

                        // Prepare the second statement to update the users table
                        $stmt2 = $conn->prepare("UPDATE users SET status = 'active', deleted_at = NULL WHERE id = ?");
                        if ($stmt2) {
                            $stmt2->bind_param("i", $id);
                            if (!$stmt2->execute()) {
                                throw new Exception("Error executing the query: " . $stmt2->error);
                            }
                            $stmt2->close();
                        } else {
                            throw new Exception("Error preparing the SQL statement: " . $conn->error);
                        }
                
                        // Commit transaction
                        $conn->commit();
                
                        echo json_encode(['status' => 'success', 'message' => "Records restored successfully."]);
                    } catch (Exception $e) {
                        // Rollback transaction on error
                        $conn->rollback();
                        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
                    }
                }

                break;

            case 'save_agent':

               // Sanitize and validate input data
                    $firstName = htmlspecialchars($_POST['first_name'], ENT_QUOTES, 'UTF-8');
                    $lastName = htmlspecialchars($_POST['last_name'], ENT_QUOTES, 'UTF-8');
                    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
                    $phone = htmlspecialchars($_POST['phone'], ENT_QUOTES, 'UTF-8');
                    $username = htmlspecialchars($_POST['username'], ENT_QUOTES, 'UTF-8');
                    $cnic = !empty($_POST['cnic']) ? htmlspecialchars($_POST['cnic'], ENT_QUOTES, 'UTF-8') : null;
                    $passport = !empty($_POST['passport']) ? htmlspecialchars($_POST['passport'], ENT_QUOTES, 'UTF-8') : null;
                    $whatsapp_number = !empty($_POST['whatsapp_number']) ? htmlspecialchars($_POST['whatsapp_number'], ENT_QUOTES, 'UTF-8') : null;
                    
                    // Validate inputs
                    if (empty($firstName) || empty($lastName) || empty($email) || empty($phone) || empty($username) || empty($whatsapp_number)) {
                        echo json_encode(["status" => "error", "message" => "All fields are required."]);
                        exit();
                    }
                    
                    // Validate email
                    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                        echo json_encode(["status" => "error", "message" => "Invalid email format."]);
                        exit();
                    }
                    // Begin transaction
                    $conn->begin_transaction();
                    
                    try {
                        // Generate hashed password
                        $passwordHash = password_hash($username, PASSWORD_DEFAULT);
                        $created_at = date('Y-m-d H:i:s');
                        $updated_at = date('Y-m-d H:i:s');
                    
                        // Prepare and execute the SQL statement for users
                        $stmt = $conn->prepare("INSERT INTO users (first_name, last_name, username, email, phone, password, role, cnic, passport, whatsapp, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                    
                        if (!$stmt) {
                            throw new Exception("Error preparing the statement: " . $conn->error);
                        }
                    
                        $role = 'agent'; // Hardcoded role for now
                        $stmt->bind_param("ssssssssssss", $firstName, $lastName, $username, $email, $phone, $passwordHash, $role, $cnic, $passport, $whatsapp_number, $created_at, $updated_at);
                    
                        $stmt->execute();
                    
                        // Check for insertion errors
                        if ($stmt->error) {
                            throw new Exception("User insertion error: " . $stmt->error);
                        }
                    
                        // Get the ID of the inserted user
                        $userId = $stmt->insert_id;
                    
                       // Assuming you have a valid $conn (database connection) and $userId defined somewhere
                        if (isset($_FILES['user_file']) && !empty($_FILES['user_file']['name'][0])) {
                            try {
                                // Call the upload function and pass the database connection
                                $uploadResults = uploadMultipleFiles('user_file', $userId, $conn);
                        
                                // Handle results
                                foreach ($uploadResults as $result) {
                                    if ($result['status'] === 'success') {
                                        echo "File uploaded successfully: " . $result['file_path'] . "<br>";
                                    } else {
                                        echo "Error: " . $result['message'] . "<br>";
                                    }
                                }
                            } catch (Exception $e) {
                                echo "An error occurred: " . $e->getMessage();
                            }
                        }

                    
                        // Commit transaction
                        $conn->commit();
                        echo json_encode(["status" => "success", "message" => "Record added successfully."]);
                    
                    } catch (Exception $e) {
                        // Rollback transaction on error
                        $conn->rollback();
                        echo json_encode(["status" => "error", "message" => "Error: " . $e->getMessage()]);
                    } finally {
                        // Close the statement and connection
                        if (isset($stmt)) $stmt->close();
                        if (isset($stmtUpdate)) $stmtUpdate->close();
                    }


                break;
                case 'update_agent':
                    // Sanitize and validate input data
                    $firstName = htmlspecialchars($_POST['first_name'], ENT_QUOTES, 'UTF-8');
                    $lastName = htmlspecialchars($_POST['last_name'], ENT_QUOTES, 'UTF-8');
                    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
                    $phone = htmlspecialchars($_POST['phone'], ENT_QUOTES, 'UTF-8');
                    $username = htmlspecialchars($_POST['username'], ENT_QUOTES, 'UTF-8');
                    $cnic = !empty($_POST['cnic']) ? htmlspecialchars($_POST['cnic'], ENT_QUOTES, 'UTF-8') : null;
                    $passport = !empty($_POST['passport']) ? htmlspecialchars($_POST['passport'], ENT_QUOTES, 'UTF-8') : null;
                    $whatsapp_number = !empty($_POST['whatsapp_number']) ? htmlspecialchars($_POST['whatsapp_number'], ENT_QUOTES, 'UTF-8') : null;
                    $id = htmlspecialchars($_POST['id'], ENT_QUOTES, 'UTF-8');
                    
                    // Validate inputs
                    if (empty($firstName) || empty($lastName) || empty($email) || empty($phone) || empty($username) || empty($whatsapp_number)) {
                        echo json_encode(["status" => "error", "message" => "All fields are required."]);
                        exit();
                    }
                    
                    // Validate email
                    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                        echo json_encode(["status" => "error", "message" => "Invalid email format."]);
                        exit();
                    }
                    
                    $updated_at = date('Y-m-d H:i:s');
                    
                    try {
                        // Start transaction
                        $conn->begin_transaction();
                        
                        // Fetch the existing image path
                        $stmtFetchImage = $conn->prepare("SELECT user_image FROM users WHERE id = ?");
                        $stmtFetchImage->bind_param("i", $id);
                        $stmtFetchImage->execute();
                        $stmtFetchImage->bind_result($existingImagePath);
                        $stmtFetchImage->fetch();
                        $stmtFetchImage->close();
                    
                        // Update user information excluding the password
                        $stmtUpdateUser = $conn->prepare("UPDATE users SET first_name=?, last_name=?, username=?, email=?, phone=?, role=?, cnic=?, passport=?, whatsapp=?, updated_at=? WHERE id=?");
                        $stmtUpdateUser->bind_param("sssssssi", $firstName, $lastName, $username, $email, $phone, $role, $cnic, $passport, $whatsapp_number, $updated_at, $id);
                        $stmtUpdateUser->execute();
                    
                        if ($stmtUpdateUser->error) {
                            throw new Exception("User update error: " . $stmtUpdateUser->error);
                        }
                    
                        $stmtUpdateUser->close();
                        
                        // Initialize image path variable
                        $imagePath = null;
                    
                        if (isset($_FILES['user_image']) && $_FILES['user_image']['error'] === UPLOAD_ERR_OK) {
                            // Upload the image
                            $uploadResult = uploadImage('user_image', $id);
                    
                            if ($uploadResult['status'] === 'success') {
                                $imagePath = $uploadResult['file_path'];
                            } else {
                                throw new Exception($uploadResult['message']);
                            }
                    
                            // Remove the existing image if it exists
                            if ($existingImagePath && file_exists($existingImagePath)) {
                                unlink($existingImagePath);
                            }
                    
                            // Update the user record with the new image path
                            $stmtUpdateImage = $conn->prepare("UPDATE users SET user_image = ? WHERE id = ?");
                            if (!$stmtUpdateImage) {
                                throw new Exception("Database prepare error: " . $conn->error);
                            }
                    
                            $stmtUpdateImage->bind_param("si", $imagePath, $id);
                            if (!$stmtUpdateImage->execute()) {
                                throw new Exception("Database execution error: " . $stmtUpdateImage->error);
                            }
                    
                            $stmtUpdateImage->close();
                        }
                    
                        // Commit transaction
                        $conn->commit();
                    
                        // Provide feedback or redirect
                        echo json_encode(["status" => "success", "message" => "Record updated successfully."]);
                    
                    } catch (Exception $e) {
                        // Rollback transaction in case of error
                        $conn->rollback();
                        echo json_encode(["status" => "error", "message" => "Error: " . $e->getMessage()]);
                    }


                break;
                
                case 'delete_team_lead':
                    if (isset($_POST['id'])) {
                        $id = intval($_POST['id']); // Use intval to sanitize the ID input
                    
                        // Start transaction
                        $conn->begin_transaction();
                    
                        try {
                            // Prepare the second statement to update the users table
                            $stmt2 = $conn->prepare("UPDATE users SET status = 'inactive', deleted_at = NOW() WHERE id = ?");
                            if ($stmt2) {
                                $stmt2->bind_param("i", $id);
                                if (!$stmt2->execute()) {
                                    throw new Exception("Error executing the query: " . $stmt2->error);
                                }
                                $stmt2->close();
                            } else {
                                throw new Exception("Error preparing the SQL statement: " . $conn->error);
                            }
                    
                            // Commit transaction
                            $conn->commit();
                    
                            echo json_encode(['status' => 'success', 'message' => "Team Lead deleted successfully."]);
                        } catch (Exception $e) {
                            // Rollback transaction on error
                            $conn->rollback();
                            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
                        }
                    }

                break;

                case 'restore_team_lead':
                    if (isset($_POST['id'])) {
                        $id = intval($_POST['id']); // Use intval to sanitize the ID input
                    
                        // Start transaction
                        $conn->begin_transaction();
                    
                        try {
    
                            // Prepare the second statement to update the users table
                            $stmt2 = $conn->prepare("UPDATE users SET status = 'active', deleted_at = NULL WHERE id = ?");
                            if ($stmt2) {
                                $stmt2->bind_param("i", $id);
                                if (!$stmt2->execute()) {
                                    throw new Exception("Error executing the query: " . $stmt2->error);
                                }
                                $stmt2->close();
                            } else {
                                throw new Exception("Error preparing the SQL statement: " . $conn->error);
                            }
                    
                            // Commit transaction
                            $conn->commit();
                    
                            echo json_encode(['status' => 'success', 'message' => "Team Lead restored successfully."]);
                        } catch (Exception $e) {
                            // Rollback transaction on error
                            $conn->rollback();
                            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
                        }
                    }

                break;

                case 'save_team_lead':
                    // Sanitize and validate input data
                    $firstName = htmlspecialchars($_POST['first_name'], ENT_QUOTES, 'UTF-8');
                    $lastName = htmlspecialchars($_POST['last_name'], ENT_QUOTES, 'UTF-8');
                    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
                    $phone = htmlspecialchars($_POST['phone'], ENT_QUOTES, 'UTF-8');
                    $username = htmlspecialchars($_POST['username'], ENT_QUOTES, 'UTF-8');
                    
                    // Validate inputs
                    if (empty($firstName) || empty($lastName) || empty($email) || empty($phone) || empty($username)) {
                        echo json_encode(["status" => "error", "message" => "All fields are required."]);
                        exit();
                    }
                    
                    // Validate email
                    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                        echo json_encode(["status" => "error", "message" => "Invalid email format."]);
                        exit();
                    }
                    
                    // Begin transaction
                    $conn->begin_transaction();
                    
                    try {
                        // Generate hashed password
                        $passwordHash = password_hash($username, PASSWORD_DEFAULT);
                        // Now use the current time in Dubai timezone
                        $created_at = date('Y-m-d H:i:s');
                        $updated_at = date('Y-m-d H:i:s');
                    
                        // Prepare and execute the SQL statement for users
                        $stmt = $conn->prepare("INSERT INTO users (first_name, last_name, username, email, phone, password, role, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
                        if (!$stmt) {
                            throw new Exception("Error preparing the statement: " . $conn->error);
                        }
                    
                        $role = 'team_lead'; // Hardcoded role for now
                        $stmt->bind_param("sssssssss", $firstName, $lastName, $username, $email, $phone, $passwordHash, $role, $created_at, $updated_at);
                        $stmt->execute();
                    
                        // Check for insertion errors
                        if ($stmt->error) {
                            throw new Exception("User insertion error: " . $stmt->error);
                        }
                    
                        // Commit transaction
                        $conn->commit();
                        echo json_encode(["status" => "success", "message" => "Team Lead added successfully."]);
                    } catch (Exception $e) {
                        // Rollback transaction on error
                        $conn->rollback();
                        echo json_encode(["status" => "error", "message" => "Error: " . $e->getMessage()]);
                    } finally {
                        // Close the statement and connection
                        if (isset($stmt)) $stmt->close();
                    }

                break;
                case 'update_team_lead':
                    // Sanitize and validate input data
                    $firstName = htmlspecialchars($_POST['first_name'], ENT_QUOTES, 'UTF-8');
                    $lastName = htmlspecialchars($_POST['last_name'], ENT_QUOTES, 'UTF-8');
                    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
                    $phone = htmlspecialchars($_POST['phone'], ENT_QUOTES, 'UTF-8');
                    $username = htmlspecialchars($_POST['username'], ENT_QUOTES, 'UTF-8');
                    $role = htmlspecialchars($_POST['role'], ENT_QUOTES, 'UTF-8');
                    $id = htmlspecialchars($_POST['id'], ENT_QUOTES, 'UTF-8');
                    
                    // Validate inputs
                    if (empty($firstName) || empty($lastName) || empty($email) || empty($phone) || empty($username) || empty($role)) {
                        echo json_encode(["status" => "error", "message" => "All fields are required."]);
                        exit();
                    }
                    
                    // Validate email
                    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                        echo json_encode(["status" => "error", "message" => "Invalid email format."]);
                        exit();
                    }
                     $updated_at = date('Y-m-d H:i:s');
                    try {
                        // Start transaction
                        $conn->begin_transaction();
                    
                        // Update user information excluding the password
                        $stmt = $conn->prepare("UPDATE users SET first_name=?, last_name=?, username=?, email=?, phone=?, role=?, updated_at=? WHERE id=?");
                        $stmt->bind_param("sssssssi", $firstName, $lastName, $username, $email, $phone, $role, $updated_at, $id);
                    
                        $stmt->execute();
                    
                        if ($stmt->error) {
                            throw new Exception("User update error: " . $stmt->error);
                        }
                    
                        $stmt->close();
                    
                        // Commit transaction
                        $conn->commit();
                    
                        // Provide feedback or redirect
                        echo json_encode(["status" => "success", "message" => "Team Lead updated successfully."]);
                    } catch (Exception $e) {
                        // Rollback transaction in case of error
                        $conn->rollback();
                        echo json_encode(["status" => "error", "message" => "Error: " . $e->getMessage()]);
                    }

                break;
                
                 case 'delete_team':
                    if (isset($_POST['id'])) {
                        $id = intval($_POST['id']); // Use intval to sanitize the ID input
                    
                        // Start transaction
                        $conn->begin_transaction();
                    
                        try {
                            // Prepare the second statement to update the users table
                            $stmt2 = $conn->prepare("UPDATE teams SET status = 'inactive', deleted_at = NOW() WHERE id = ?");
                            if ($stmt2) {
                                $stmt2->bind_param("i", $id);
                                if (!$stmt2->execute()) {
                                    throw new Exception("Error executing the query: " . $stmt2->error);
                                }
                                $stmt2->close();
                            } else {
                                throw new Exception("Error preparing the SQL statement: " . $conn->error);
                            }
                    
                            // Commit transaction
                            $conn->commit();
                    
                            echo json_encode(['status' => 'success', 'message' => "Team deleted successfully."]);
                        } catch (Exception $e) {
                            // Rollback transaction on error
                            $conn->rollback();
                            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
                        }
                    }

                break;

                case 'restore_team':
                    if (isset($_POST['id'])) {
                        $id = intval($_POST['id']); // Use intval to sanitize the ID input
                    
                        // Start transaction
                        $conn->begin_transaction();
                    
                        try {
    
                            // Prepare the second statement to update the users table
                            $stmt2 = $conn->prepare("UPDATE teams SET status = 'active', deleted_at = NULL WHERE id = ?");
                            if ($stmt2) {
                                $stmt2->bind_param("i", $id);
                                if (!$stmt2->execute()) {
                                    throw new Exception("Error executing the query: " . $stmt2->error);
                                }
                                $stmt2->close();
                            } else {
                                throw new Exception("Error preparing the SQL statement: " . $conn->error);
                            }
                    
                            // Commit transaction
                            $conn->commit();
                    
                            echo json_encode(['status' => 'success', 'message' => "Team restored successfully."]);
                        } catch (Exception $e) {
                            // Rollback transaction on error
                            $conn->rollback();
                            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
                        }
                    }

                break;

                case 'save_team':
                 // Sanitize and validate input data
                    $name = htmlspecialchars($_POST['name'], ENT_QUOTES, 'UTF-8');
                    $team_lead = intval($_POST['team_lead']); // Assuming team_lead is a single value
                    $agents = $_POST['agents']; // agents is an array
                    
                    // Validate inputs
                    if (empty($name) || empty($team_lead) || !is_array($agents) || count($agents) === 0) {
                        echo json_encode(["status" => "error", "message" => "All fields are required and agents must be an array."]);
                        exit();
                    }
                    
                    // Set Dubai timezone
                    date_default_timezone_set('Asia/Dubai');
                    $created_at = date('Y-m-d H:i:s');
                    $updated_at = date('Y-m-d H:i:s');
                    
                    // Begin transaction
                    $conn->begin_transaction();
                    
                    try {
                        // Prepare and execute the SQL statement for teams
                        $stmt = $conn->prepare("INSERT INTO teams (name, team_lead_id, created_at, updated_at) VALUES (?, ?, ?, ?)");
                        if (!$stmt) {
                            throw new Exception("Error preparing the statement: " . $conn->error);
                        }
                    
                        // Bind the parameters (s for string, i for integer, s for datetime)
                        $stmt->bind_param("siss", $name, $team_lead, $created_at, $updated_at);
                        $stmt->execute();
                    
                        // Check for insertion errors
                        if ($stmt->error) {
                            throw new Exception("Team insertion error: " . $stmt->error);
                        }
                    
                        // Get the last inserted team ID
                        $team_id = $stmt->insert_id;
                        $stmt->close(); // Close the insert statement
                    
                        // Update the agents with the new team_id
                        if (count($agents) > 0) {
                            // Create placeholders for the agent IDs
                            $placeholders = implode(',', array_fill(0, count($agents), '?'));
                       
                            $sql = "UPDATE users SET team_id = ?, updated_at = ? WHERE id IN ($placeholders)";
                            $stmt = $conn->prepare($sql);
                    
                            if (!$stmt) {
                                throw new Exception("Error preparing the update statement: " . $conn->error);
                            }
                    
                            // Prepare the binding types: 'i' for team_id, 's' for updated_at, and 'i' for each agent ID
                            $types = 'is' . str_repeat('i', count($agents)); // 'i' for each agent's ID
                            $params = array_merge([$team_id, $updated_at], $agents); // Merge team_id, updated_at, and agent IDs
                    
                            // Bind parameters dynamically
                            $stmt->bind_param($types, ...$params);
                            $stmt->execute();
                    
                            // Check for errors
                            if ($stmt->error) {
                                throw new Exception("Agent update error: " . $stmt->error);
                            }
                    
                            $stmt->close(); // Close the update statement
                        }
                    
                        // Commit the transaction after both operations are successful
                        $conn->commit();
                        echo json_encode(["status" => "success", "message" => "Team and agents updated successfully."]);
                    
                    } catch (Exception $e) {
                        // Rollback transaction on error
                        $conn->rollback();
                        echo json_encode(["status" => "error", "message" => "Error: " . $e->getMessage()]);
                    } finally {
                        // Close the statement if it was initialized
                        if (isset($stmt)) {
                            $stmt->close();
                        }
                    }
                break;
                case 'update_team':
                  // Sanitize and validate input data
                    $name = htmlspecialchars($_POST['name'], ENT_QUOTES, 'UTF-8');
                    $id = intval($_POST['id']); // Team ID
                    $agents = $_POST['agents']; // Agents sent in the request (an array of agent IDs)
                    
                    // Validate inputs
                    if (empty($name) || empty($agents)) {
                        echo json_encode(["status" => "error", "message" => "All fields are required."]);
                        exit();
                    }
                    
                    $updated_at = date('Y-m-d H:i:s');
                    
                    try {
                        // Start transaction
                        $conn->begin_transaction();
                        
                        // Update team information
                        $stmt = $conn->prepare("UPDATE teams SET name=?, updated_at=? WHERE id=?");
                        if (!$stmt) {
                            throw new Exception("Error preparing the statement: " . $conn->error);
                        }
                    
                        // Bind parameters (s for string, i for integer)
                        $stmt->bind_param("ssi", $name, $updated_at, $id);
                        $stmt->execute();
                        
                        if ($stmt->error) {
                            throw new Exception("Team update error: " . $stmt->error);
                        }
                        
                        // Close the update statement
                        $stmt->close();
                    
                        // Step 1: Fetch currently mapped agent IDs for this team
                        $stmt = $conn->prepare("SELECT id FROM users WHERE team_id = ?");
                        if (!$stmt) {
                            throw new Exception("Error preparing fetch agents statement: " . $conn->error);
                        }
                        
                        $stmt->bind_param("i", $id);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        
                        $current_agent_ids = [];
                        while ($row = $result->fetch_assoc()) {
                            $current_agent_ids[] = $row['id'];
                        }
                        
                        $stmt->close(); // Close fetch statement
                        
                        // Step 2: Determine which agents to add or remove
                        $agents_to_add = array_diff($agents, $current_agent_ids);  // New agents
                        $agents_to_remove = array_diff($current_agent_ids, $agents);  // Agents to be removed
                    
                        // Step 3: Update the users table for agents that need to be added to this team
                        if (!empty($agents_to_add)) {
                            $in_clause = implode(',', array_fill(0, count($agents_to_add), '?'));
                            $stmt = $conn->prepare("UPDATE users SET team_id = ?, updated_at = ? WHERE id IN ($in_clause)");
                            
                            if (!$stmt) {
                                throw new Exception("Error preparing add agents statement: " . $conn->error);
                            }
                    
                            $types = 'is' . str_repeat('i', count($agents_to_add));
                            $params = array_merge([$id, $updated_at], $agents_to_add);
                            $stmt->bind_param($types, ...$params);
                            $stmt->execute();
                            
                            if ($stmt->error) {
                                throw new Exception("Error adding agents: " . $stmt->error);
                            }
                    
                            $stmt->close(); // Close add agents statement
                        }
                    
                        // Step 4: Update the users table for agents that need to be removed from this team
                        if (!empty($agents_to_remove)) {
                            $in_clause = implode(',', array_fill(0, count($agents_to_remove), '?'));
                            $stmt = $conn->prepare("UPDATE users SET team_id = NULL, updated_at = ? WHERE id IN ($in_clause)");
                            
                            if (!$stmt) {
                                throw new Exception("Error preparing remove agents statement: " . $conn->error);
                            }
                    
                            $types = 's' . str_repeat('i', count($agents_to_remove));
                            $params = array_merge([$updated_at], $agents_to_remove);
                            $stmt->bind_param($types, ...$params);
                            $stmt->execute();
                            
                            if ($stmt->error) {
                                throw new Exception("Error removing agents: " . $stmt->error);
                            }
                    
                            $stmt->close(); // Close remove agents statement
                        }
                    
                        // Commit transaction
                        $conn->commit();
                    
                        // Provide feedback or redirect
                        echo json_encode(["status" => "success", "message" => "Team and agents updated successfully."]);
                    
                    } catch (Exception $e) {
                        // Rollback transaction in case of error
                        $conn->rollback();
                        echo json_encode(["status" => "error", "message" => "Error: " . $e->getMessage()]);
                    }
                break;
                case 'remove_userImage':
                    //remove user image
                    $imagePath = $_POST['imagePath'] ?? '';
                    $userId = $_POST['userId'] ?? '';
                    
                    // Validate and sanitize the input
                    $imagePath = filter_var($imagePath, FILTER_SANITIZE_URL);
                    $userId = filter_var($userId, FILTER_SANITIZE_NUMBER_INT);
                    
                    if ($imagePath && $userId) {
                        // Get the absolute path to the image
                        $absolutePath = realpath(__DIR__ . '/../' . $imagePath);
                    
                        // Ensure the file exists and is within a safe directory
                        if (file_exists($absolutePath) && strpos($absolutePath, realpath(__DIR__ . '/../uploads')) === 0) { // Ensure the file is in the 'uploads' directory
                            if (unlink($absolutePath)) {
                                // Update the user's database record to remove the image path
                                $stmt = $conn->prepare("UPDATE users SET user_image = NULL WHERE id = ?");
                                $stmt->bind_param("i", $userId);
                    
                                if ($stmt->execute()) {
                                    echo json_encode(['status' => 'success']);
                                } else {
                                    echo json_encode(['status' => 'error', 'message' => 'Failed to update the database.']);
                                }
                    
                                $stmt->close();
                            } else {
                                echo json_encode(['status' => 'error', 'message' => 'Unable to delete the image.']);
                            }
                        } else {
                            echo json_encode(['status' => 'error', 'message' => 'Image file does not exist or is in an invalid location.']);
                        }
                    
                    } else {
                        echo json_encode(['status' => 'error', 'message' => 'Invalid image path or user ID.']);
                    }
                break;

                default:
                    echo json_encode(['status' => 'error', 'message' => 'Invalid action.']);
                break;
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Action not set in POST request.']);
    }
    
    
    // Close the database connection
    $conn->close();
    exit();
}

function uploadMultipleFiles($fileInputName, $userId, $conn, $baseDir = 'dist/assets/uploads/') {
    $uploadedFiles = [];
    $year = date('Y');
    $month = date('m');
    $day = date('d');
    $imageDir = $baseDir . $year . '/' . $month . '/' . $day . '/' . $userId . '/';

    // Ensure the directory exists
    if (!is_dir($imageDir)) {
        if (!mkdir($imageDir, 0777, true)) {
            throw new Exception("Failed to create directory: $imageDir");
        }
    }

    // Loop through each file in the input array
    for ($i = 0; $i < count($_FILES[$fileInputName]['name']); $i++) {
        $fileName = basename($_FILES[$fileInputName]['name'][$i]);
        $fileTmpPath = $_FILES[$fileInputName]['tmp_name'][$i];
        $fileError = $_FILES[$fileInputName]['error'][$i];

        // Handle file errors
        if ($fileError !== UPLOAD_ERR_OK) {
            $uploadedFiles[] = ["status" => "error", "message" => "Error with file upload code " . $fileError];
            continue;
        }

        $filePath = $imageDir . $fileName;

        // Move the uploaded file to the destination directory
        if (move_uploaded_file($fileTmpPath, $filePath)) {
            // Extract the file extension
            $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);

            // Prepare the SQL statement for inserting file record
            $stmt = $conn->prepare("INSERT INTO user_files (user_id, file_path, type, created_at, updated_at) VALUES (?, ?, ?, NOW(), NOW())");

            if (!$stmt) {
                $uploadedFiles[] = ["status" => "error", "message" => "Error preparing the statement: " . $conn->error];
                continue;
            }

            // Bind parameters with file extension in the type column
            $stmt->bind_param("sss", $userId, $filePath, $fileExtension);

            if ($stmt->execute()) {
                $uploadedFiles[] = ["status" => "success", "file_path" => $filePath];
            } else {
                $uploadedFiles[] = ["status" => "error", "message" => "File insertion error: " . $stmt->error];
            }

            // Close the statement
            $stmt->close();
        } else {
            $uploadedFiles[] = ["status" => "error", "message" => "Error moving file: $fileName"];
        }
    }

    return $uploadedFiles;
}


?>
