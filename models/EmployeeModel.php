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

    // METHOD TAMBAHAN: Get statistik gaji per departemen untuk Tugas 1
    public function getSalaryStats() {
        $query = "select department, round(avg(salary), 2) as avg_salary, max(salary) as max_salary, min(salary) as min_salary, count(*) as total_employees from employees group by department order by avg_salary desc";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // METHOD TAMBAHAN: Get statistik masa kerja karyawan untuk Tugas 1 (Agregat)
    public function getTenureStats() {
        $query = "select 
                    case 
                        when extract(year from age(current_date, hire_date)) < 1 then 'Junior (< 1 tahun)'
                        when extract(year from age(current_date, hire_date)) between 1 and 3 then 'Middle (1-3 tahun)'
                        else 'Senior (> 3 tahun)'
                    end as experience_category,
                    count(*) as total_employees,
                    string_agg(concat(first_name, ' ', last_name), ', ' order by first_name asc) as employee_names
                  from employees 
                  group by 1 
                  order by 
                    case 
                        when min(extract(year from age(current_date, hire_date))) < 1 then 1
                        when min(extract(year from age(current_date, hire_date))) <= 3 then 2
                        else 3
                    end";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // METHOD TAMBAHAN: Get daftar setiap karyawan beserta masa kerjanya
    public function getEmployeesWithTenure() {
        $query = "select 
                    id,
                    concat(first_name, ' ', last_name) as full_name,
                    department,
                    position,
                    hire_date,
                    extract(year from age(current_date, hire_date))::int as tenure_years,
                    extract(month from age(current_date, hire_date))::int as tenure_months,
                    case 
                        when extract(year from age(current_date, hire_date)) < 1 then 'Junior'
                        when extract(year from age(current_date, hire_date)) between 1 and 3 then 'Middle'
                        else 'Senior'
                    end as experience_category
                  from employees 
                  order by hire_date asc";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // METHOD TAMBAHAN: Get ringkasan karyawan secara keseluruhan untuk Tugas 1
    public function getEmployeeOverview() {
        $query = "select 
                    count(*) as total_employees,
                    sum(salary) as total_monthly_salary,
                    round(avg(extract(year from age(current_date, hire_date))), 1) as avg_years_of_service
                  from employees";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>
