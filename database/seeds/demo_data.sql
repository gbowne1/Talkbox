-- Seed demo users
INSERT INTO users (username, password_hash, status, last_seen)
VALUES
  ('alice', '$2y$10$abcdefghijklmnopqrstuv1234567890abcdefghi', 'online', NOW()),
  ('bob',   '$2y$10$abcdefghijklmnopqrstuv1234567890abcdefghi', 'offline', NOW()),
  ('charlie','$2y$10$abcdefghijklmnopqrstuv1234567890abcdefghi', 'offline', NOW());

-- Note: Replace the password_hash values with real hashes generated via PHP's password_hash()

-- Seed demo rooms
INSERT INTO rooms (name, created_at)
VALUES
  ('General', NOW()),
  ('Tech Talk', NOW()),
  ('Random', NOW());

-- Seed demo messages
INSERT INTO messages (user_id, text, created_at)
VALUES
  (1, 'Hello everyone, welcome to TalkBox!', NOW()),
  (2, 'Hi Alice, glad to be here!', NOW()),
  (3, 'What’s up folks?', NOW());
