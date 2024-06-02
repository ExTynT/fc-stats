-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hostiteľ: 127.0.0.1
-- Čas generovania: So 01.Jún 2024, 20:21
-- Verzia serveru: 10.4.32-MariaDB
-- Verzia PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Databáza: `fc_stats`
--

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `comments`
--

CREATE TABLE `comments` (
  `id` int(11) NOT NULL,
  `news_id` int(11) NOT NULL,
  `user_id` int(10) UNSIGNED DEFAULT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Sťahujem dáta pre tabuľku `comments`
--

INSERT INTO `comments` (`id`, `news_id`, `user_id`, `content`, `created_at`) VALUES
(1069, 10, 6, 'ds', '2024-05-26 18:31:43'),
(1071, 10, 6, 'da', '2024-05-26 18:31:57'),
(1073, 16, 6, 'asd', '2024-05-26 18:32:26'),
(1077, 13, 6, 'sad dsa dsa', '2024-05-27 13:53:24'),
(1081, 16, 6, 'dsa', '2024-05-27 14:25:02'),
(1082, 10, 6, 's', '2024-05-27 14:26:25'),
(1084, 16, 6, 'dsa', '2024-05-27 15:10:53'),
(1088, 15, 6, 'ads', '2024-06-01 11:55:53'),
(1089, 15, 6, 'ads', '2024-06-01 11:57:32'),
(1091, 15, 6, 'asd', '2024-06-01 11:58:36'),
(1095, 12, 6, 'sad', '2024-06-01 12:01:53'),
(1097, 12, 6, 'sad', '2024-06-01 12:02:25'),
(1098, 12, 6, 'sad', '2024-06-01 12:03:08'),
(1102, 18, 6, 'ads', '2024-06-01 12:04:50'),
(1104, 18, 6, 'ads', '2024-06-01 12:06:29'),
(1106, 17, 6, 'ads', '2024-06-01 12:07:23'),
(1107, 14, 6, 'das', '2024-06-01 12:07:26'),
(1108, 14, 6, 'sad', '2024-06-01 12:12:53'),
(1109, 14, 6, 'sad', '2024-06-01 12:13:49'),
(1110, 11, 6, 'ads', '2024-06-01 12:13:52'),
(1111, 11, 6, 'sad', '2024-06-01 12:14:46'),
(1112, 18, 6, 'ads', '2024-06-01 12:14:53'),
(1113, 13, 6, 'asd', '2024-06-01 12:18:14'),
(1115, 13, 20, 'dfdf', '2024-06-01 12:18:54'),
(1116, 11, 6, 'd', '2024-06-01 16:49:06');

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `h2h_1`
--

CREATE TABLE `h2h_1` (
  `H2H_1_ID` int(11) NOT NULL,
  `H2H_1_Datum` date NOT NULL,
  `H2H_1_Liga` varchar(5) NOT NULL,
  `H2H_1_Zapas` varchar(55) NOT NULL,
  `H2H_1_Vysledok` varchar(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Sťahujem dáta pre tabuľku `h2h_1`
--

INSERT INTO `h2h_1` (`H2H_1_ID`, `H2H_1_Datum`, `H2H_1_Liga`, `H2H_1_Zapas`, `H2H_1_Vysledok`) VALUES
(1, '2024-04-13', 'LL', 'Barcelona vs PSG', '0:1'),
(2, '2023-06-25', 'L1', 'PSG vs Barcelona', '2:1'),
(3, '2024-07-16', 'LL', 'Barcelona vs PSG', '3:3'),
(4, '2024-01-13', 'L1', 'PSG vs Barcelona', '0:0');

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `h2h_2`
--

CREATE TABLE `h2h_2` (
  `H2H_2_ID` int(11) NOT NULL,
  `H2H_2_Datum` date NOT NULL,
  `H2H_2_Liga` varchar(5) NOT NULL,
  `H2H_2_Zapas` varchar(25) NOT NULL,
  `H2H_2_Vysledok` varchar(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Sťahujem dáta pre tabuľku `h2h_2`
--

INSERT INTO `h2h_2` (`H2H_2_ID`, `H2H_2_Datum`, `H2H_2_Liga`, `H2H_2_Zapas`, `H2H_2_Vysledok`) VALUES
(5, '2024-03-04', 'BL', 'Dortmund vs Atletico', '4:1'),
(6, '2023-11-18', 'LL', 'Atletico vs Dortmund', '2:3'),
(7, '2022-07-16', 'BL', 'Dortmund vs Atletico', '3:4'),
(8, '2020-01-13', 'LL', 'Atletico vs Dortmund', '2:1');

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `h2h_3`
--

CREATE TABLE `h2h_3` (
  `H2H_3_ID` int(11) NOT NULL,
  `H2H_3_Datum` date DEFAULT NULL,
  `H2H_3_Liga` varchar(255) DEFAULT NULL,
  `H2H_3_Zapas` varchar(255) DEFAULT NULL,
  `H2H_3_Vysledok` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Sťahujem dáta pre tabuľku `h2h_3`
--

INSERT INTO `h2h_3` (`H2H_3_ID`, `H2H_3_Datum`, `H2H_3_Liga`, `H2H_3_Zapas`, `H2H_3_Vysledok`) VALUES
(1, '2023-11-22', 'Liga Majstrov', 'Bergamo - Leverkusen', '2:1'),
(2, '2023-05-12', 'Bundesliga', 'Leverkusen - Bergamo', '1:0'),
(3, '2022-08-28', 'Priateľský zápas', 'Bergamo - Leverkusen', '3:3'),
(4, '2022-02-14', 'Európska liga', 'Leverkusen - Bergamo', '0:2');

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `h2h_table`
--

CREATE TABLE `h2h_table` (
  `h2h_id` int(11) NOT NULL,
  `match_id` int(11) NOT NULL,
  `h2h_date` date DEFAULT NULL,
  `h2h_league` varchar(255) DEFAULT NULL,
  `h2h_match` varchar(255) DEFAULT NULL,
  `h2h_result` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Sťahujem dáta pre tabuľku `h2h_table`
--

INSERT INTO `h2h_table` (`h2h_id`, `match_id`, `h2h_date`, `h2h_league`, `h2h_match`, `h2h_result`, `created_at`) VALUES
(31, 30, '2024-02-21', '1. Liga', 'Barca vs Madrid', '4:0', '2024-06-01 17:49:06'),
(32, 30, '2024-02-21', '1. Liga', 'Barca vs Madrid', '4:0', '2024-06-01 17:49:06'),
(33, 31, '2024-05-18', 'CL', 'Real Madrid vs Borussia', '3:2', '2024-06-01 18:04:08'),
(34, 31, '2024-05-18', 'CL', 'Real Madrid vs Borussia', '3:2', '2024-06-01 18:04:08');

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `matches_table`
--

CREATE TABLE `matches_table` (
  `id` int(11) NOT NULL,
  `time` time NOT NULL,
  `team1` varchar(255) NOT NULL,
  `team2` varchar(255) NOT NULL,
  `logo1` varchar(255) DEFAULT NULL,
  `logo2` varchar(255) DEFAULT NULL,
  `competition` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Sťahujem dáta pre tabuľku `matches_table`
--

INSERT INTO `matches_table` (`id`, `time`, `team1`, `team2`, `logo1`, `logo2`, `competition`, `created_at`) VALUES
(30, '20:30:00', 'Barca', 'Madrid', 'FCB_LOGO_1.png', 'bvb_1.png', 'LaLiga', '2024-06-01 17:49:06'),
(31, '21:00:00', 'Dortmund', 'Real Madrid', 'bvb_2.png', 'real_madrid_1.png', 'CL', '2024-06-01 18:04:08');

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `news`
--

CREATE TABLE `news` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `category` varchar(50) NOT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Sťahujem dáta pre tabuľku `news`
--

INSERT INTO `news` (`id`, `title`, `content`, `category`, `image_path`, `created_at`) VALUES
(10, ' Window Deadline Day Madness', 'Messi skoruje! ', 'Champions League', '../img/messi.jpg', '2024-05-23 13:38:15'),
(11, 'Surprise Upset in Europa League', 'Underdogs Sevilla stun Manchester United with a 2-1 win in the quarter-finals.', 'Europa League', '../img/sevilla.jpg', '2024-05-23 13:38:15'),
(12, 'Mbappe Transfer Saga Continues', 'Kylian Mbappe\'s potential move to Real Madrid dominates transfer headlines.', 'Transfers', '../img/mpabbe.jpg', '2024-05-23 13:38:15'),
(13, 'Ronaldo Breaks Another Record', 'Cristiano Ronaldo sets a new Champions League goalscoring record.', 'Champions League', '../img/ronaldo.jpg', '2024-05-23 13:38:15'),
(14, 'Europa League Final Preview', 'Arsenal and Villarreal gear up for the Europa League final showdown.dsa', 'Europa League', '../img/av.jpg', '2024-05-23 13:38:15'),
(15, 'Breaking Transfer News: Haaland to Chelsea?', 'Reports suggest Erling Haaland is close to joining Chelsea in a record-breaking deal.', 'Transfers', '../img/haaland.jpg', '2024-05-23 13:38:15'),
(16, 'Transfer Window Deadline Day Madness', 'khkhkj\r\n', 'Champions League', '../img/liverpool.jpg', '2024-05-23 13:38:15'),
(17, 'Europa League Dark Horses Emerge', 'Several unexpected teams are making waves in the Europa League this season.', 'Europa League', '../img/europa.jpg', '2024-05-23 13:38:15'),
(18, 'Transfer Window Deadline Day Madness', 'A roundup of the biggest deals and surprises on transfer deadline day.', 'Transfers', '../img/transfer.jpg', '2024-05-23 13:38:15');

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `previews_table`
--

CREATE TABLE `previews_table` (
  `preview_id` int(11) NOT NULL,
  `match_id` int(11) NOT NULL,
  `preview_text` text DEFAULT NULL,
  `preview_odds_win_1` decimal(5,2) DEFAULT NULL,
  `preview_odds_draw` decimal(5,2) DEFAULT NULL,
  `preview_odds_win_2` decimal(5,2) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Sťahujem dáta pre tabuľku `previews_table`
--

INSERT INTO `previews_table` (`preview_id`, `match_id`, `preview_text`, `preview_odds_win_1`, `preview_odds_draw`, `preview_odds_win_2`, `created_at`) VALUES
(32, 30, 'Lol', 1.45, 4.52, 2.51, '2024-06-01 17:49:06'),
(33, 30, 'Lol', 1.45, 4.52, 2.51, '2024-06-01 17:49:06'),
(34, 31, 'Veľkolepá bitka medzi velikánmi futbalu!', 4.77, 4.07, 1.74, '2024-06-01 18:04:08'),
(35, 31, 'Veľkolepá bitka medzi velikánmi futbalu!', 4.77, 4.07, 1.74, '2024-06-01 18:04:08');

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `preview_1`
--

CREATE TABLE `preview_1` (
  `preview_1_id` int(11) NOT NULL,
  `preview_1_text` varchar(10000) NOT NULL,
  `preview_1_odds_win_1` float NOT NULL,
  `preview_1_odds_win_2` float NOT NULL,
  `preview_1_odds_draw` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Sťahujem dáta pre tabuľku `preview_1`
--

INSERT INTO `preview_1` (`preview_1_id`, `preview_1_text`, `preview_1_odds_win_1`, `preview_1_odds_win_2`, `preview_1_odds_draw`) VALUES
(2, ' Leading 3-2 on aggregate and hosting this second leg of the quarter-final, Barcelona are in a commanding position as they welcome PSG. The Catalan giants are poised to secure their first UEFA Champions League (UCL) semi-final appearance since 2018/19 following their victory in the previous leg, a match marked by ups and downs where Xavi’s team initially took the lead, then fell behind, only to rally and emerge triumphant.\n            <br>\n            <br>\n            This outcome grants Barcelona the advantage of knowing that a draw will suffice to advance. With a favorable track record at home against French opponents (W9, D2, L2), they should feel confident about their prospects. However, a recent 4-1 defeat at home against PSG casts a shadow of uncertainty over their minds, reminding them that the tie is far from over despite their historical success in progressing 37 out of 39 times after winning the first leg away in UEFA competitions.\n            <br>\n            <br>\n            For PSG, overturning a deficit of four goals from the first leg might seem daunting, but there is precedent in this fixture. Only one previous occasion in UCL history has witnessed such a comeback, and it occurred in this very matchup, giving PSG hope that a two-goal victory within regulation time is within reach, especially with talents like Kylian Mbappé in their ranks.\n            <br>\n            <br>\n            However, memories of a past defeat linger for PSG, as they suffered a 6-1 loss in the away leg when current Barcelona manager Luis Enrique was in charge, underscoring their mediocre away record against Spanish opponents (W6, D3, L9). Given PSG\'s tendency to lose the away leg in UEFA competition ties where they faltered in the first leg at home, it\'s likely that their away loss tally will increase, possibly reaching double digits.\n            <br>\n            <br>\n            Key players to watch include Raphinha, who netted his first-ever UCL goals in the first leg, and PSG\'s Mbappé, who secured a hat-trick in their previous encounter held at Barcelona\'s home ground and has shown prowess in scoring in Spain, as evidenced by his brace against Real Sociedad in the round of 16.\n            <br>\n            <br>\n            <b>An interesting statistic to note is that in 11 of PSG’s last 12 UCL away matches, both teams found the net, indicating a trend worth considering.</b>', 2.13, 3.09, 4.25);

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `preview_2`
--

CREATE TABLE `preview_2` (
  `preview_2_id` int(11) NOT NULL,
  `preview_2_text` varchar(10000) NOT NULL,
  `preview_2_odds_win_1` float NOT NULL,
  `preview_2_odds_win_2` float NOT NULL,
  `preview_2_odds_draw` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Sťahujem dáta pre tabuľku `preview_2`
--

INSERT INTO `preview_2` (`preview_2_id`, `preview_2_text`, `preview_2_odds_win_1`, `preview_2_odds_win_2`, `preview_2_odds_draw`) VALUES
(1, '<p>In a pivotal clash for a spot in this season\'s UEFA Champions League (UCL) semi-finals, Borussia Dortmund aim to overturn a 2-1 deficit from the first leg against Spanish powerhouse Atlético Madrid. The first leg encounter leveled the head-to-head record between these teams (W3, D1, L3), and Dortmund, buoyed by a recent resurgence with six wins in eight competitive matches, enter this match with optimism.<br><br>Although Dortmund\'s record against Spanish opponents isn\'t the strongest (W11, D12, L14), they have secured eight of those victories on home turf (W8, D7, L3). Overcoming a psychological barrier, Dortmund have been eliminated in each of their last five UCL quarter-final appearances, but their history of progressing in eight out of 18 European ties after losing the away first leg suggests a tangible chance.<br><br>Atlético Madrid strengthened their claim for a La Liga top-four finish with a convincing 3-1 win over Girona, marking their third consecutive victory. Diego Simeone, the long-serving manager, has guided Atlético to the UCL semi-finals on three occasions before, with the last one dating back to 2016/17. Another semi-final appearance would elevate Simeone alongside Sir Alex Ferguson in reaching the most semi-finals with the same club.<br><br>However, Atlético faces a daunting task in Germany, having suffered seven defeats in their last ten visits (W2, D1, L7), including losses in their last four games, all under Simeone\'s tenure. Their defensive solidity will be tested, as they have managed just one clean sheet in their last 16 competitive away matches, including three UCL outings (W1, D1, L1).<br><br>Key players to watch include Dortmund\'s Karim Adeyemi, who will aim to make an impact before serving a domestic ban, and Atlético\'s Antoine Griezmann, tasked with leading the scoring line in the absence of Depay and Lino. Griezmann arrives in fine form after netting a league brace over the weekend and has previously found the net against Dortmund for both Barcelona and Atlético.<br><br><b>Dortmund\'s home record in the UCL remains formidable, boasting an unbeaten streak of nine matches (W5, D4), which stands as their longest such run in the competition\'s history.</b></p>', 2.27, 3.77, 3.1);

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `preview_3`
--

CREATE TABLE `preview_3` (
  `preview_3_id` int(11) NOT NULL,
  `preview_3_text` text DEFAULT NULL,
  `preview_3_odds_win_1` decimal(5,2) DEFAULT NULL,
  `preview_3_odds_win_2` decimal(5,2) DEFAULT NULL,
  `preview_3_odds_draw` decimal(5,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Sťahujem dáta pre tabuľku `preview_3`
--

INSERT INTO `preview_3` (`preview_3_id`, `preview_3_text`, `preview_3_odds_win_1`, `preview_3_odds_win_2`, `preview_3_odds_draw`) VALUES
(1, 'Bergamo and Leverkusen clash in a crucial Champions League encounter, a match that could significantly impact their chances of progressing to the knockout stages. Both teams have been in impressive form recently, setting the stage for a closely contested battle.\n<br>Bergamo, renowned for their attacking flair and high-pressing style, will be eager to exploit any defensive vulnerabilities in the Leverkusen backline. Their dynamic front three, led by the in-form striker Duván Zapata, poses a constant threat to any opposition.\n<br>\nLeverkusen, however, are no pushovers. Their well-organized defense, anchored by the experienced Jonathan Tah, is known for its resilience.  Leverkusen also boasts a potent counter-attacking threat, with the pace and trickery of Moussa Diaby and Leon Bailey capable of turning defense into attack in the blink of an eye.<br>\n<br>\nThe midfield battle promises to be a fascinating subplot. Both teams have creative playmakers who can unlock defenses with a single pass. Bergamo\'s Ruslan Malinovskyi and Leverkusen\'s Florian Wirtz are players to watch, as their ability to dictate tempo and create chances could prove decisive.<br>\n<br>\nWith the stakes high and both teams desperate for a positive result, expect a thrilling encounter filled with end-to-end action.  The match has all the ingredients for a goal-fest, and it wouldn\'t be surprising to see both teams find the back of the net. The outcome could hinge on fine margins, with individual brilliance or a defensive lapse potentially deciding the fate of this crucial Champions League fixture.', 2.50, 2.80, 3.20);

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `role` varchar(50) NOT NULL DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Sťahujem dáta pre tabuľku `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `created_at`, `role`) VALUES
(1, 'Ivko', '$2y$10$jzRc5fATIf7rqkI8s.pqOueQ4/4HNUvqdbGFo8bIfQRzMtCVy2TbC', '2024-05-23 14:36:19', 'user'),
(2, 's', '$2y$10$FSMAELXoza.Zeq/UZT/xk.RTAAfzPeT5Qz/wFBD9ItqARZKlLYQHq', '2024-05-23 14:37:10', 'user'),
(3, 'ssda', '$2y$10$URZBrBQaClEnDmjJdzbpwOTEHx1jfBui.JNtX/EKnAcagnWesm.V6', '2024-05-23 16:09:28', 'user'),
(4, 'Ahoj', '$2y$10$/YNw2.31tgCbLUNgXW8vauvu.ZarnhDfoeZNnhsemlJxgBsJ/sv56', '2024-05-23 16:09:58', 'user'),
(5, 'admin', '$2y$10$tdpWxCZINvV4EDsqM2AASuOfWMIuVLzDXGrZYa8AswOALnFcMrsWK', '2024-05-23 16:23:46', 'user'),
(6, 'boss', '$2y$10$H3//QsUdFPmElo5eUa5pB.PKHEtsI09H5k4pSvJ0R/AEjjE1ZiSd.', '2024-05-23 16:32:25', 'admin'),
(9, 'asas', '$2y$10$xOuK0li874HAUUzDnJPLj.aefTOnsXg1EQ63Z.PQnkjrKcmi3/zz2', '2024-05-23 17:32:52', 'user'),
(15, 'auto', '$2y$10$H3UdlmPjnJqbEhCpBNMUNOQBNI2d5KIjmtzMqT2P/2zdLJYyur7nC', '2024-05-24 15:07:39', 'user'),
(16, 'saso', '$2y$10$vbi4nQkyl5shMwN4eea6Ke4hI1fdeMsh.YYGBZOPvvy6c/HaQOIJq', '2024-05-24 15:09:50', 'user'),
(17, '123', '$2y$10$jylHDOjOvH4qY4WU88VQTeE.XfxxQuAjNhrIWq8iPMWDjkC5uz53S', '2024-05-24 15:19:11', 'user'),
(18, 'coko', '$2y$10$QCoJ2pzmuEUyarAIDTQ6V.HiAXdw2P7GORYX9N6JqDZOZ5CNDTqOS', '2024-05-24 15:23:41', 'user'),
(19, 'asdfg', '$2y$10$Qt97.xyXe.D8TrxfShQBK.CjeTaYUKR.hVy5xILO.RTpRlRjD.iTu', '2024-05-24 15:24:14', 'user'),
(20, 'skuska', '$2y$10$Y7Y74HHKrNnhEjnqjzcGGOO4HotB1w9gnoX2O90zMizh4elHWcYgm', '2024-05-24 15:41:07', 'user'),
(21, 'loko', '$2y$10$gnoKj5knvSF9UEv3jZ4Fuu7EABtJ8qXFC59.R58UKJD6PDSTMV8JC', '2024-05-25 12:49:48', 'user'),
(22, 'skuskaaa', '$2y$10$XStcW7jl7rBrrnskxB5H2e6jCHUSXLj7bCBKxmFqNXXuvtVBTcXUi', '2024-05-27 15:18:47', 'user');

--
-- Kľúče pre exportované tabuľky
--

--
-- Indexy pre tabuľku `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `news_id` (`news_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexy pre tabuľku `h2h_1`
--
ALTER TABLE `h2h_1`
  ADD PRIMARY KEY (`H2H_1_ID`);

--
-- Indexy pre tabuľku `h2h_2`
--
ALTER TABLE `h2h_2`
  ADD PRIMARY KEY (`H2H_2_ID`);

--
-- Indexy pre tabuľku `h2h_3`
--
ALTER TABLE `h2h_3`
  ADD PRIMARY KEY (`H2H_3_ID`);

--
-- Indexy pre tabuľku `h2h_table`
--
ALTER TABLE `h2h_table`
  ADD PRIMARY KEY (`h2h_id`),
  ADD KEY `match_id` (`match_id`);

--
-- Indexy pre tabuľku `matches_table`
--
ALTER TABLE `matches_table`
  ADD PRIMARY KEY (`id`);

--
-- Indexy pre tabuľku `news`
--
ALTER TABLE `news`
  ADD PRIMARY KEY (`id`);

--
-- Indexy pre tabuľku `previews_table`
--
ALTER TABLE `previews_table`
  ADD PRIMARY KEY (`preview_id`),
  ADD KEY `match_id` (`match_id`);

--
-- Indexy pre tabuľku `preview_1`
--
ALTER TABLE `preview_1`
  ADD PRIMARY KEY (`preview_1_id`);

--
-- Indexy pre tabuľku `preview_2`
--
ALTER TABLE `preview_2`
  ADD PRIMARY KEY (`preview_2_id`);

--
-- Indexy pre tabuľku `preview_3`
--
ALTER TABLE `preview_3`
  ADD PRIMARY KEY (`preview_3_id`);

--
-- Indexy pre tabuľku `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `idx_username` (`username`);

--
-- AUTO_INCREMENT pre exportované tabuľky
--

--
-- AUTO_INCREMENT pre tabuľku `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1117;

--
-- AUTO_INCREMENT pre tabuľku `h2h_1`
--
ALTER TABLE `h2h_1`
  MODIFY `H2H_1_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pre tabuľku `h2h_2`
--
ALTER TABLE `h2h_2`
  MODIFY `H2H_2_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT pre tabuľku `h2h_3`
--
ALTER TABLE `h2h_3`
  MODIFY `H2H_3_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pre tabuľku `h2h_table`
--
ALTER TABLE `h2h_table`
  MODIFY `h2h_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT pre tabuľku `matches_table`
--
ALTER TABLE `matches_table`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT pre tabuľku `news`
--
ALTER TABLE `news`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT pre tabuľku `previews_table`
--
ALTER TABLE `previews_table`
  MODIFY `preview_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT pre tabuľku `preview_1`
--
ALTER TABLE `preview_1`
  MODIFY `preview_1_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pre tabuľku `preview_2`
--
ALTER TABLE `preview_2`
  MODIFY `preview_2_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pre tabuľku `preview_3`
--
ALTER TABLE `preview_3`
  MODIFY `preview_3_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pre tabuľku `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- Obmedzenie pre exportované tabuľky
--

--
-- Obmedzenie pre tabuľku `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`news_id`) REFERENCES `news` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Obmedzenie pre tabuľku `h2h_table`
--
ALTER TABLE `h2h_table`
  ADD CONSTRAINT `h2h_table_ibfk_1` FOREIGN KEY (`match_id`) REFERENCES `matches_table` (`id`) ON DELETE CASCADE;

--
-- Obmedzenie pre tabuľku `previews_table`
--
ALTER TABLE `previews_table`
  ADD CONSTRAINT `previews_table_ibfk_1` FOREIGN KEY (`match_id`) REFERENCES `matches_table` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
