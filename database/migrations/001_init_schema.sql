-- Удаление таблиц в правильном порядке (чтобы не было ошибок из-за внешних ключей)
DROP TABLE IF EXISTS remember_tokens CASCADE;
DROP TABLE IF EXISTS likes CASCADE;
DROP TABLE IF EXISTS comments CASCADE;
DROP TABLE IF EXISTS game_media CASCADE;
DROP TABLE IF EXISTS game_tags CASCADE;
DROP TABLE IF EXISTS tags CASCADE;
DROP TABLE IF EXISTS games CASCADE;
DROP TABLE IF EXISTS users CASCADE;

-- 1. Пользователи
CREATE TABLE users (
                       id SERIAL PRIMARY KEY,
                       login VARCHAR(50) UNIQUE NOT NULL,
                       password_hash VARCHAR(255) NOT NULL,
                       nickname VARCHAR(100) NOT NULL,
                       bio TEXT,
                       avatar_url VARCHAR(255),
                       banner_url VARCHAR(255),
                       social_links JSONB DEFAULT '{}',
                       theme VARCHAR(10) DEFAULT 'dark' CHECK (theme IN ('light', 'dark')),
                       lang VARCHAR(5) DEFAULT 'ru' CHECK (lang IN ('ru', 'en')),
                       created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 2. Игры (проекты)
CREATE TABLE games (
                       id SERIAL PRIMARY KEY,
                       author_id INT NOT NULL REFERENCES users(id) ON DELETE CASCADE,
                       title VARCHAR(255) NOT NULL,
                       short_description VARCHAR(500),
                       full_description TEXT,
                       engine VARCHAR(100),
                       genre VARCHAR(100),
                       release_date DATE,
                       cover_url VARCHAR(255),
                       created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                       updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Индекс для быстрого поиска игр по названию
CREATE INDEX idx_games_title ON games(title);

-- 3. Теги
CREATE TABLE tags (
                      id SERIAL PRIMARY KEY,
                      name VARCHAR(100) UNIQUE NOT NULL,
                      description TEXT
);

-- 4. Связь игр и тегов (Многие ко многим)
CREATE TABLE game_tags (
                           game_id INT NOT NULL REFERENCES games(id) ON DELETE CASCADE,
                           tag_id INT NOT NULL REFERENCES tags(id) ON DELETE CASCADE,
                           PRIMARY KEY (game_id, tag_id)
);

-- 5. Медиафайлы игр (скриншоты, видео, гифки)
CREATE TABLE game_media (
                            id SERIAL PRIMARY KEY,
                            game_id INT NOT NULL REFERENCES games(id) ON DELETE CASCADE,
                            url VARCHAR(255) NOT NULL,
                            type VARCHAR(20) NOT NULL CHECK (type IN ('image', 'video', 'gif')),
                            sort_order INT DEFAULT 0,
                            is_cover BOOLEAN DEFAULT FALSE
);

-- 6. Комментарии
CREATE TABLE comments (
                          id SERIAL PRIMARY KEY,
                          game_id INT NOT NULL REFERENCES games(id) ON DELETE CASCADE,
                          user_id INT NOT NULL REFERENCES users(id) ON DELETE CASCADE,
                          content TEXT NOT NULL,
                          created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 7. Лайки
CREATE TABLE likes (
                       user_id INT NOT NULL REFERENCES users(id) ON DELETE CASCADE,
                       game_id INT NOT NULL REFERENCES games(id) ON DELETE CASCADE,
                       PRIMARY KEY (user_id, game_id)
);

-- 8. Токены "Запомнить меня"
CREATE TABLE remember_tokens (
                                 id SERIAL PRIMARY KEY,
                                 user_id INT NOT NULL REFERENCES users(id) ON DELETE CASCADE,
                                 token VARCHAR(255) UNIQUE NOT NULL,
                                 expires_at TIMESTAMP NOT NULL
);