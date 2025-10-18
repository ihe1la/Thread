-- Create tables
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    is_admin BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE security_toggles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    sanitization_level ENUM('none', 'basic', 'strict') DEFAULT 'strict',
    allow_svg_upload BOOLEAN DEFAULT FALSE,
    allow_external_fetch BOOLEAN DEFAULT FALSE,
    csp_enabled BOOLEAN DEFAULT TRUE,
    require_strict_redirect_match BOOLEAN DEFAULT TRUE,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE threads (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    content TEXT,
    image_path VARCHAR(255),
    is_flagged BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE oauth_clients (
    id INT AUTO_INCREMENT PRIMARY KEY,
    client_id VARCHAR(255) NOT NULL UNIQUE,
    client_secret VARCHAR(255) NOT NULL,
    redirect_uri TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert seed data
INSERT INTO users (username, password, is_admin) VALUES
('admin', '$2y$10$8Ux8YwSBzO5QWxETfM2COuSkr1TVs3U3XhP1ymkF6tZRkL1DAaZ0y', TRUE),  -- password: admin123
('alice', '$2y$10$vB/nLBqwxB.Vh0PVZwuJx.oPUE3B6FEn1aBniOAV3GJQbf2TtXB2O', FALSE), -- password: alice123
('bob', '$2y$10$XK3kJq5zAhJvX0QhQZR8wezWf0N3C5s5C.e/ZGGz.MRPF8QqRE/4q', FALSE),   -- password: bob123
('chuck', '$2y$10$YLW8FQg0Xb/EX5Kn.sbRIOqlXxY3GC8kU4kPFz3wvZ2uV9g2AXDNy', FALSE); -- password: chuck123

INSERT INTO security_toggles 
(sanitization_level, allow_svg_upload, allow_external_fetch, csp_enabled, require_strict_redirect_match) 
VALUES ('strict', FALSE, FALSE, TRUE, TRUE);

INSERT INTO threads (user_id, title, content) VALUES
(2, 'Welcome to Hackerboard', 'Hey everyone! Welcome to our new forum.'),
(3, 'Testing image upload', '<img src="test.jpg" alt="Test image">'),
(4, 'External content test', '<a href="http://example.com">Check this out</a>');

INSERT INTO oauth_clients (client_id, client_secret, redirect_uri) VALUES
('mock_client_1', 'secret123', 'http://localhost:3000/oauth/callback'),
('mock_client_2', 'secret456', 'http://localhost:3000/oauth/callback2');