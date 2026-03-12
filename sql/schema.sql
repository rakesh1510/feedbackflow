CREATE TABLE companies (
    id INT AUTO_INCREMENT PRIMARY KEY,
    company_name VARCHAR(255),
    email VARCHAR(255),
    password_hash VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE projects (
    id INT AUTO_INCREMENT PRIMARY KEY,
    company_id INT,
    project_name VARCHAR(255),
    domain VARCHAR(255),
    site_key VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE feedback (
    id INT AUTO_INCREMENT PRIMARY KEY,
    project_id INT,
    rating INT,
    message TEXT,
    page_url TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);