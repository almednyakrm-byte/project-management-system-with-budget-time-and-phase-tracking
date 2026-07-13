CREATE TABLE users (
  id INT AUTO_INCREMENT,
  username VARCHAR(255) NOT NULL,
  email VARCHAR(255) NOT NULL,
  password VARCHAR(255) NOT NULL,
  role ENUM('guest', 'user', 'admin') NOT NULL DEFAULT 'guest',
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY (email)
);

CREATE TABLE projects (
  id INT AUTO_INCREMENT,
  title VARCHAR(255) NOT NULL,
  description TEXT,
  start_date DATE,
  end_date DATE,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id)
);

CREATE TABLE team_members (
  id INT AUTO_INCREMENT,
  name VARCHAR(255) NOT NULL,
  email VARCHAR(255) NOT NULL,
  role ENUM('team_member', 'project_manager') NOT NULL DEFAULT 'team_member',
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY (email)
);

CREATE TABLE budget_planning (
  id INT AUTO_INCREMENT,
  project_id INT NOT NULL,
  budget DECIMAL(10, 2) NOT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY (project_id),
  CONSTRAINT fk_budget_planning_project FOREIGN KEY (project_id) REFERENCES projects (id)
);

CREATE TABLE user_projects (
  id INT AUTO_INCREMENT,
  user_id INT NOT NULL,
  project_id INT NOT NULL,
  role ENUM('guest', 'user', 'admin') NOT NULL DEFAULT 'guest',
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY (user_id),
  KEY (project_id),
  CONSTRAINT fk_user_projects_user FOREIGN KEY (user_id) REFERENCES users (id),
  CONSTRAINT fk_user_projects_project FOREIGN KEY (project_id) REFERENCES projects (id)
);

INSERT INTO users (username, email, password, role)
VALUES ('admin', 'admin@example.com', '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', 'admin');

INSERT INTO projects (title, description, start_date, end_date)
VALUES ('Project 1', 'This is a project', '2022-01-01', '2022-12-31');

INSERT INTO team_members (name, email, role)
VALUES ('John Doe', 'john@example.com', 'team_member');

INSERT INTO budget_planning (project_id, budget)
VALUES (1, 10000.00);

INSERT INTO user_projects (user_id, project_id, role)
VALUES (1, 1, 'admin');