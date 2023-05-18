USE omnesMySkills;

INSERT INTO Matieres (nomMatiere) VALUES 
    ("Algèbre"), -- 1
    ("Analyse"), -- 2
    ("Probabilités"), -- 3

    ("Mécanique et Oscillations"), -- 4
    ("Electromagnétisme"), -- 5
    ("Thermodynamique"), -- 6
    ("Mécanique des fluides"), -- 7

    ("Programmation C"), -- 8
    ("Théorie des graphes et base de données relationnelles"), -- 9
    ("Programmation Web"), -- 10

    ("Électronique Numérique et Analogique"), -- 11
    ("Électronique Fondamentale"), -- 12

    ("Ingénieur dans l'entreprise"), -- 13
    ("RSE"), -- 14

    ("Anglais"); -- 15

INSERT INTO Ecoles (nomEcole,typeEtude) VALUES
    ("ECE", "Ingénieur");  -- 1

INSERT INTO Filieres (nomFiliere,idEcole) VALUES
    ("Ingé", 1), -- 1
    ("Bachelor", 1); -- 2

INSERT INTO Promotions (annee,idFiliere) VALUES
    (2023,1), -- 1
    (2024,1), -- 2
    (2025,1), -- 3
    (2026,1), -- 4
    (2027,1), -- 5

    (2026,2), -- 6
    (2027,2); -- 7

INSERT INTO Classes (numGroupe,effectif,idPromo) VALUES
    (1,NULL,4), -- 1
    (2,NULL,4), -- 2
    (3,NULL,4); -- 3

INSERT INTO Users (typeAccount,email,nomUser,prenomUser,hashPassword,idClasse) VALUES

    (2,"prof1Maths@ece.fr","Mister","Maths","$2y$10$RXJSyFaDbFxrWtFjTmHFTucAm8.bV407lLn2X1wMW8IRXsgXrRfFe",NULL), -- mdp:prof1Maths -- 1
    (2,"prof2Maths@ece.fr","Madame","Maths","$2y$10$IEAqS23b3Htrlx7t4JLQceKbbjzf1DyxghNqe5wYqqC6SE4TvHNNO",NULL), -- mdp:prof2Maths -- 2
    (2,"prof1physique@ece.fr","Mister","Physique","$2y$10$wMUIxk2lSt7JgYMylPUPzO.IHBz5rcbFAbDDimbX4exdZ8ZO34lHS",NULL), -- mdp:prof1Physique -- 3
    (2,"prof1Info@ece.fr","Mister","Info","$2y$10$rHGw4jfW6UNMPv3rIWM.A.nvxC5ZbIH/6uufh/DbMgyUDSGm8k40C",NULL), -- mdp:prof1Info -- 4
    (2,"prof1Elec@ece.fr","Mister","Elec","$2y$10$sPgqPm/vbhv6nS4XlC1bCOWHOqSzE8u/xpfS039h.LnaeMsbLMoCq",NULL), -- mdp:prof1Elec -- 5
    (2,"prof2Elec@ece.fr","Madame","Elec","$2y$10$QbOFTr8i3MT2FEpoTVyi8OUT2rY8LU6C5C68xVqjfsN6PVlnyv2YS",NULL), -- mdp:prof2Elec -- 6
    (2,"prof1Humanité@ece.fr","Mister","Humanité","$2y$10$bjxHPFcnJq8mwCIzJi7qH.IpSCN0koZGlJ7l.kUO6ztbR7.YK.sbK",NULL), -- mdp:prof1Humanité -- 7
    (2,"prof2Humanité@ece.fr","Madame","Humanité","$2y$10$mecfXPv7PNFuW9lTB6rgE.sMYXUpCv0qsYB6bvz7sjHVohGDIhPOO",NULL), -- mdp:prof2Humanité -- 8
    (2,"prof1Langues@ece.fr","Mister","Langues","$2y$10$176cbX87PWmbi1GgDAWVM.6FlGU1El4AEcleaHN3fHduWOnikr6DS",NULL), -- mdp:prof1Langues -- 9

    (0,"admin1@ece.fr","LeToutPuissant","Lucas","$2y$10$NjjrShOX1B/6WsPBaT3f1OPpehtIk1HVEAQ.2QlM6fDgAZHLLUBim",NULL), -- mdp:lukeh -- 10
    (1,"user1@ece.fr","LaSalope","Tiffany","$2y$10$NjjrShOX1B/6WsPBaT3f1OPpehtIk1HVEAQ.2QlM6fDgAZHLLUBim",2), -- mdp:lukeh -- 11
    (1,"user2@ece.fr","thm","thm","$2y$10$NjjrShOX1B/6WsPBaT3f1OPpehtIk1HVEAQ.2QlM6fDgAZHLLUBim",1); -- mdp:lukeh -- 12


INSERT INTO Cours (volumeHoraire, idClasse, idMatiere, idProfesseur) VALUES
    -- Maths
    ("36.50", 1, 1, 1), -- 1
    ("36.50", 2, 1, 1), -- 2
    ("36.50", 3, 1, 2), -- 3

    ("35.00", 1, 2, 1), -- 4
    ("35.00", 2, 2, 1), -- 5
    ("35.00", 3, 2, 2), -- 6

    ("16.50", 1, 3, 1), -- 7
    ("16.50", 2, 3, 1), -- 8
    ("16.50", 3, 3, 2), -- 9

    -- Physique
    ("100.00", 1, 4, 3), -- 10
    ("100.00", 2, 4, 3), -- 11
    ("100.00", 3, 4, 3), -- 12

    ("66.00", 1, 5, 3), -- 13
    ("66.00", 2, 5, 3), -- 14
    ("66.00", 3, 5, 3), -- 15

    ("6.00", 1, 6, 3), -- 16
    ("6.00", 2, 6, 3), -- 17
    ("6.00", 3, 6, 3), -- 18

    ("2.00", 1, 7, 3), -- 19
    ("2.00", 2, 7, 3), -- 20
    ("2.00", 3, 7, 3), -- 21

    -- Info
    ("52.00", 1, 8, 4), -- 22
    ("52.00", 2, 8, 4), -- 23
    ("52.00", 3, 8, 4), -- 24

    ("42.00", 1, 9, 4), -- 25
    ("42.00", 2, 9, 4), -- 26
    ("42.00", 3, 9, 4), -- 27

    ("29.00", 1, 10, 4), -- 28
    ("29.00", 2, 10, 4), -- 29
    ("29.00", 3, 10, 4), -- 30

    -- Elec
    ("33.00", 1, 11, 5), -- 31
    ("33.00", 2, 11, 5), -- 32
    ("33.00", 3, 11, 5), -- 33

    ("33.00", 1, 12, 6), -- 34
    ("33.00", 2, 12, 6), -- 35
    ("33.00", 3, 12, 6), -- 36

    -- Humanités
    ("25.00", 1, 13, 7), -- 37
    ("25.00", 2, 13, 7), -- 38
    ("25.00", 3, 13, 7), -- 39

    ("20.00", 1, 14, 8), -- 40
    ("20.00", 2, 14, 8), -- 41
    ("20.00", 3, 14, 8), -- 42

    -- Anglais
    ("16.50", 1, 15, 9), -- 43
    -- ("16.50", 2, 15, 9), -- 44
    ("16.50", 3, 15, 9); -- 45

INSERT INTO Competences (nomCompetences,DateCreation) VALUES
    ("Aisance à l'oral","2023-05-14 00:00:00"), -- 1
    ("Travail en groupe","2023-05-14 00:00:00"), -- 2
    ("Gestion du temps","2023-05-14 00:00:00"), -- 3
    ("PHP/SQL","2023-05-14 00:00:00"), -- 4
    ("Matrices","2023-05-14 00:00:00"), -- 5
    ("Nombres complexes","2023-05-14 00:00:00"), -- 6
    ("Maxwell","2023-05-14 00:00:00"), -- 7
    ("Pointeur de structures","2023-05-14 00:00:00"), -- 8
    ("Sensibilisation Environnementale","2023-05-14 00:00:00"); -- 9

INSERT INTO Themes (nomTheme) VALUES
    ("Logique"), -- 1
    ("Savoir-être"), -- 2
    ("Organisation"), -- 3
    ("Internationale"), -- 4
    ("Monde du travail"); -- 5

INSERT INTO MatiereCompetences (idCompetences,idMatiere) VALUES
    (1,13),
    (1,14),
    (1,15),
    
    (2,8),
    (2,9),
    (2,10),
    (2,11),
    (2,12),
    (2,13),
    (2,14),
    (2,15),

    (3,1),
    (3,2),
    (3,3),
    (3,4),
    (3,5),
    (3,6),
    (3,7),
    (3,8),
    (3,9),
    (3,10),
    (3,11),
    (3,12),
    (3,13),
    (3,14),
    (3,15),

    (4,9),
    (4,10),

    (5,1),

    (6,2),

    (7,5),

    (8,8),

    (9,13),
    (9,14);


INSERT INTO ThemesCompetences (idCompetences,idTheme) VALUES
    (1,2),
    (1,4),
    (1,5),

    (2,2),
    (2,3),
    (2,4),
    (2,5),

    (3,3),
    (3,5),

    (4,1),

    (5,1),

    (6,1),

    (7,1),

    (8,1),

    (9,4),
    (9,5);