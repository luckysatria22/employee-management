<?php
/**
 * FILE: models/EmployeeModel.php
 * FUNGSI: Berisi semua operasi database untuk tabel employees
 */
class EmployeeModel {
    private $conn;
    private $table_name = "employees";

    // Constructor
    public function __construct($db) {
        $this->conn = $db;
    }

    // METHOD 1: Read semua employees
    public function getAllEmployees() {
        $query = "select * from " . $this->table_name . " order by id desc";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // METHOD 2: Create employee baru
    public function createEmployee($data) {
        $query = "insert into " . $this->table_name . " (first_name, last_name, email, department, position, salary, hire_date) values (:first_name, :last_name, :email, :department, :position, :salary, :hire_date)";
        $stmt = $this->conn->prepare($query);
        
        // Bind parameters untuk keamanan (mencegah SQL injection)
        $stmt->bindParam(":first_name", $data['first_name']);
        $stmt->bindParam(":last_name", $data['last_name']);
        $stmt->bindParam(":email", $data['email']);
        $stmt->bindParam(":department", $data['department']);
        $stmt->bindParam(":position", $data['position']);
        $stmt->bindParam(":salary", $data['salary']);
        $stmt->bindParam(":hire_date", $data['hire_date']);
        
        return $stmt->execute();
    }

    // METHOD 3: Update employee
    public function updateEmployee($id, $data) {
        $query = "update " . $this->table_name . " set first_name = :first_name, last_name = :last_name, email = :email, department = :department, position = :position, salary = :salary, hire_date = :hire_date where id = :id";
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(":id", $id);
        $stmt->bindParam(":first_name", $data['first_name']);
        $stmt->bindParam(":last_name", $data['last_name']);
        $stmt->bindParam(":email", $data['email']);
        $stmt->bindParam(":department", $data['department']);
        $stmt->bindParam(":position", $data['position']);
        $stmt->bindParam(":salary", $data['salary']);
        $stmt->bindParam(":hire_date", $data['hire_date']);
        
        return $stmt->execute();
    }

    // METHOD 4: Delete employee
    public function deleteEmployee($id) {
        $query = "delete from " . $this->table_name . " where id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        return $stmt->execute();
    }

    // METHOD 5: Get single employee by ID
    public function getEmployeeById($id) {
        $query = "select * from " . $this->table_name . " where id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // METHOD 6: Get data dari VIEW employee_summary
    public function getEmployeeSummary() {
        $query = "select * from employee_summary";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // METHOD 7: Get data dari VIEW department_stats
    public function getDepartmentStats() {
        $query = "select * from department_stats";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // METHOD 8: Get data dari MATERIALIZED VIEW dashboard_summary
    public function getDashboardSummary() {
        $query = "select * from dashboard_summary";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // METHOD 9: Refresh materialized view
    public function refreshDashboard() {
        $query = "refresh materialized view dashboard_summary";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute();
    }
}
?>
