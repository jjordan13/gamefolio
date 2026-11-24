-- Очистка таблиц (если нужно перезапустить)
TRUNCATE TABLE likes, comments, game_media, game_tags, games, tags, users RESTART IDENTITY CASCADE;

-- Пользователи (пароль для всех: "password")
INSERT INTO users (login, password_hash, nickname, bio, theme, lang) VALUES
                                                                         ('alex_dev', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Alex Developer', 'Indie game developer, Unity enthusiast', 'dark', 'en'),
                                                                         ('maria_artist', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Maria 3D', '3D artist and concept designer', 'light', 'ru'),
                                                                         ('john_designer', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'John Game', 'Game designer with 5 years experience', 'dark', 'en'),
                                                                         ('anna_programmer', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Anna Code', 'Unreal Engine programmer', 'dark', 'ru'),
                                                                         ('mike_sound', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Mike Audio', 'Sound designer and composer', 'light', 'en');

-- Теги
INSERT INTO tags (name, description) VALUES
                                         ('Action', 'Action games'),
                                         ('RPG', 'Role-playing games'),
                                         ('Strategy', 'Strategy games'),
                                         ('Adventure', 'Adventure games'),
                                         ('Puzzle', 'Puzzle games'),
                                         ('Horror', 'Horror games'),
                                         ('Unity', 'Games made with Unity'),
                                         ('Unreal Engine', 'Games made with Unreal Engine'),
                                         ('Godot', 'Games made with Godot'),
                                         ('GameMaker', 'Games made with GameMaker'),
                                         ('Programmer', 'Programming role'),
                                         ('Artist', 'Art role'),
                                         ('Designer', 'Design role'),
                                         ('Sound Designer', 'Sound design role');

-- Игры
INSERT INTO games (author_id, title, short_description, full_description, engine, genre) VALUES
                                                                                             (1, 'Space Runner', 'Fast-paced space shooter', 'A thrilling space shooter with procedural generation and multiple weapon types.', 'Unity', 'Action'),
                                                                                             (1, 'Dungeon Quest', 'Classic RPG adventure', 'Explore dungeons, fight monsters, and collect loot in this classic RPG.', 'Unity', 'RPG'),
                                                                                             (2, 'Fantasy World', 'Beautiful 3D fantasy environment', 'A showcase of 3D modeling and texturing skills in a fantasy setting.', 'Unreal Engine', 'Adventure'),
                                                                                             (2, 'Character Collection', 'Various character models', 'A portfolio of character designs and 3D models for games.', 'Blender', 'Adventure'),
                                                                                             (3, 'Puzzle Kingdom', 'Strategic puzzle game', 'Build your kingdom while solving challenging puzzles.', 'Godot', 'Puzzle'),
                                                                                             (3, 'Mystery Manor', 'Point-and-click mystery', 'Solve the mystery of the abandoned manor in this atmospheric adventure.', 'GameMaker', 'Adventure'),
                                                                                             (4, 'Battle Arena', 'Multiplayer combat game', 'Fast-paced multiplayer battles with various game modes.', 'Unreal Engine', 'Action'),
                                                                                             (4, 'Racing Simulator', 'Realistic racing experience', 'High-fidelity racing simulation with realistic physics.', 'Unreal Engine', 'Action'),
                                                                                             (5, 'Ambient Worlds', 'Atmospheric soundscapes', 'Collection of ambient soundtracks for games and meditation.', 'FMOD', 'Adventure'),
                                                                                             (5, 'Sound Effects Pack', 'Game SFX library', 'Comprehensive library of sound effects for indie games.', 'Audacity', 'Puzzle');

-- Связи игр и тегов
INSERT INTO game_tags (game_id, tag_id) VALUES
                                            (1, 1), (1, 7), (1, 11),
                                            (2, 2), (2, 7), (2, 11),
                                            (3, 4), (3, 8), (3, 12),
                                            (4, 4), (4, 12),
                                            (5, 5), (5, 9), (5, 13),
                                            (6, 4), (6, 6), (6, 10), (6, 13),
                                            (7, 1), (7, 8), (7, 11),
                                            (8, 1), (8, 8), (8, 11),
                                            (9, 4), (9, 14),
                                            (10, 5), (10, 14);

-- Медиафайлы (используем placeholder изображения)
INSERT INTO game_media (game_id, url, type, sort_order, is_cover) VALUES
                                                                      (1, 'https://via.placeholder.com/800x450/1a1a2e/ffffff?text=Space+Runner+1', 'image', 1, TRUE),
                                                                      (1, 'https://via.placeholder.com/800x450/16213e/ffffff?text=Space+Runner+2', 'image', 2, FALSE),
                                                                      (2, 'https://via.placeholder.com/800x450/0f3460/ffffff?text=Dungeon+Quest', 'image', 1, TRUE),
                                                                      (3, 'https://via.placeholder.com/800x450/533483/ffffff?text=Fantasy+World', 'image', 1, TRUE),
                                                                      (3, 'https://via.placeholder.com/800x450/e94560/ffffff?text=Fantasy+World+2', 'image', 2, FALSE),
                                                                      (4, 'https://via.placeholder.com/800x450/1a1a2e/ffffff?text=Character+1', 'image', 1, TRUE),
                                                                      (5, 'https://via.placeholder.com/800x450/16213e/ffffff?text=Puzzle+Kingdom', 'image', 1, TRUE),
                                                                      (6, 'https://via.placeholder.com/800x450/0f3460/ffffff?text=Mystery+Manor', 'image', 1, TRUE),
                                                                      (7, 'https://via.placeholder.com/800x450/533483/ffffff?text=Battle+Arena', 'image', 1, TRUE),
                                                                      (8, 'https://via.placeholder.com/800x450/e94560/ffffff?text=Racing+Sim', 'image', 1, TRUE),
                                                                      (9, 'https://via.placeholder.com/800x450/1a1a2e/ffffff?text=Ambient+Worlds', 'image', 1, TRUE),
                                                                      (10, 'https://via.placeholder.com/800x450/16213e/ffffff?text=SFX+Pack', 'image', 1, TRUE);

-- Комментарии
INSERT INTO comments (game_id, user_id, content) VALUES
                                                     (1, 2, 'Great shooter! Love the weapon variety.'),
                                                     (1, 3, 'The procedural generation is impressive.'),
                                                     (2, 4, 'Classic RPG done right. Nice work!'),
                                                     (3, 1, 'Beautiful environments, great art style.'),
                                                     (5, 5, 'Challenging puzzles, very addictive.'),
                                                     (7, 2, 'Multiplayer is smooth and fun.'),
                                                     (9, 3, 'Perfect ambient soundtracks for my projects.');

-- Лайки
INSERT INTO likes (user_id, game_id) VALUES
                                         (2, 1), (3, 1), (4, 1),
                                         (1, 2), (5, 2),
                                         (1, 3), (4, 3),
                                         (2, 5), (3, 5),
                                         (1, 7), (2, 7), (3, 7), (4, 7),
                                         (5, 9);