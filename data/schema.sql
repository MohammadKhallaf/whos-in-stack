-- SQLite database schema for queue management
CREATE TABLE IF NOT EXISTS queue_members (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL,
    email TEXT,
    phone TEXT,
    reason TEXT,
    status TEXT DEFAULT 'waiting', -- waiting, in_room, finished
    queue_number INTEGER,
    joined_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    entered_room_at DATETIME,
    finished_at DATETIME,
    estimated_time INTEGER DEFAULT 15, -- minutes
    notes TEXT
);

CREATE TABLE IF NOT EXISTS room_config (
    id INTEGER PRIMARY KEY,
    room_name TEXT DEFAULT 'Service Room',
    max_capacity INTEGER DEFAULT 1,
    average_service_time INTEGER DEFAULT 15, -- minutes
    is_open BOOLEAN DEFAULT 1,
    current_serving INTEGER
);

-- Insert default room config
INSERT INTO room_config (id, room_name) VALUES (1, 'Service Room');
