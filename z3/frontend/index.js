function colorNameToRgba(colorName, alpha = 0.5) {

    var canvas = document.createElement('canvas');
    canvas.width = canvas.height = 1;
    var ctx = canvas.getContext('2d');

    ctx.fillStyle = colorName;
    ctx.fillRect(0, 0, 1, 1);

    var data = ctx.getImageData(0, 0, 1, 1).data;

    return `rgba(${data[0]}, ${data[1]}, ${data[2]}, ${alpha})`;
}
let finished = false;
let animationFrameId = null;

let initialized = false;
let started = false;
let play = false;

let timeRemaining = 60;
let timerInterval = null;


window.addEventListener('load', e => {
    function startTimer() {
        timerInterval = setInterval(() => {
            timeRemaining--;
            updateTimerDisplay();

            if (timeRemaining <= 0) {
                clearInterval(timerInterval);
                timeRemaining = 0;
                updateTimerDisplay();
                endGame();
            }
        }, 1000);
    }
    function updateTimerDisplay() {
        const seconds = timeRemaining % 60;
        const formattedTime = `${seconds < 10 ? '0' + seconds : seconds}`;
        document.getElementById('timer').textContent = formattedTime;
    }

    function endGame(killer = null) {
        if (killer === null || killer === undefined) {
            console.log("Game finished!");
            cancelAnimationFrame(animationFrameId);
            const winner = determineWinner();
            showWinner(winner)
            console.log(`Winner: Player ${winner}`);
            finished = true;
        } else {
            console.log("Game finished by killing!");
            cancelAnimationFrame(animationFrameId);
            const winner = killer
            showWinner(winner)
            console.log(`Winner: Player ${winner}`);
            finished = true;
        }
    }

    function determineWinner() {
        if (player.occupiedArea.length > enemy.occupiedArea.length) {
            return player.id;
        } else if (player.occupiedArea.length < enemy.occupiedArea.length) {
            return enemy.id;
        } 
        // else {
        //     return null;
        // }
    }

    const socket = new WebSocket('wss://node86.webte.fei.stuba.sk/wss');
    const canvas = document.getElementById('gameCanvas');
    const ctx = canvas.getContext('2d');


    const startButton = document.getElementById('startButton');
    const startMenu = document.getElementById('startMenu');
    const winnerNotification = document.getElementById('winnerNotification');
    const winnerName = document.getElementById('winnerName');

    startButton.addEventListener('click', function () {
        startMenu.style.display = 'none';
        canvas.style.display = 'block'
        console.log("Player connected");
        const playerData = JSON.stringify({
            type: 'newPlayer',
        });
        socket.send(playerData);
    });

    function showWinner(winner = null) {
        if (winner !== null) {
            winnerName.textContent = `Player ${winner+1}`;
            winnerNotification.style.display = 'block';
            winnerNotification.classList.add(winner === 1 ? "alert-success" : "alert-danger");
            canvas.style.display = 'none'
        } else {
            winnerName.textContent = `Drow`;
            winnerNotification.style.display = 'block';
            winnerNotification.classList.add("alert-warning");
            canvas.style.display = 'none'
        }
    }



    let inCollision = 0;

    const player = {
        id: null,
        width: 16,
        height: 16,
        color: 'rgba(0, 0, 0, 0)',
        trailColor: 'rgba(0, 0, 0, 0)',
        direction: null,
        speed: 1,
        trail: [],
        occupiedArea: [],
        lastSentIndex: -1
    };

    const enemy = {
        id: null,
        width: 16,
        height: 16,
        color: 'rgba(0, 0, 0, 0)',
        trailColor: 'rgba(0, 0, 0, 0)',
        direction: null,
        speed: 1,
        trail: [],
        occupiedArea: []
    };

    socket.onopen = function () {
        console.log("Connection stabled.");
    };

    socket.onmessage = function (event) {
        let data = JSON.parse(event.data);
        switch (data.type) {
            case "playerData":
                player.uuid = data.uuid;
                player.id = data.id;
                if (data.id === 0) {
                    player.x = canvas.width / 4;
                    player.y = canvas.height / 4;
                } else if (data.id === 1) {
                    player.x = (canvas.width / 4) * 3;
                    player.y = (canvas.height / 4) * 3;
                }
                player.color = data.color;
                player.trailColor = colorNameToRgba(data.color)
                sendData(player.x, player.y, player.occupiedArea)
                initializeOccupiedArea(player, ctx)
                break;
            case "otherPlayer":
                if (data.uuid !== player.uuid) {
                    enemy.uuid = data.uuid;
                    enemy.id = data.playerId;
                    enemy.color = data.color;
                    enemy.trailColor = colorNameToRgba(data.color)
                    enemy.x = data.x;
                    enemy.y = data.y;
                    enemy.trail = data.trail;
                    addCoordinates(enemy.occupiedArea, data.occupiedArea)
                    enemy.speed = data.speed;
                    enemy.trail.push({ x: enemy.x, y: enemy.y });
                    enemy.trail.push({ x: enemy.x, y: enemy.y });

                }
                break;
            case 'gameStart':
                console.log('Game started', data)
                if (timerInterval === null) { startTimer(); }
                play = true;
                gameLoop()
                break;
            case 'gamePending':
                console.log('Game pending', data)
                break;
            case 'gameOver':
                console.log(`Game Over:`, data.killedBy);
                const killer = (data.killedBy !== undefined || data.killedBy !== null) ? data.killedBy : null
                endGame(killer)
                socket.close()
                break;
            case 'trailCleared':
                console.log(`trailCleared:`, data);
                break;
            default:
        }
        initialized = true;

    };

    socket.onclose = function (event) {
        if (event.wasClean) {
            console.log('Connection closed clear');
        } else {
            console.log('Conection refused');
        }
        console.log('Code: ' + event.code + ' reason: ' + event.reason);
    };

    socket.onerror = function (error) {
        console.log("SOCKET Error " + error.message);
    };

    function initializeOccupiedArea(player) {
        const startX = player.x - player.width * 2;
        const startY = player.y - player.height * 2;
        for (let i = 0; i < player.width * 2; i++) {
            for (let j = 0; j < player.height * 2; j++) {
                player.occupiedArea.push({ x: startX + i, y: startY + j });
            }
        }
    }
    function drawOccupiedArea(ctx, player, h = 1, w = 1) {
        ctx.fillStyle = player.trailColor;
        player.occupiedArea.forEach(segment => {
            ctx.fillRect(segment.x, segment.y, h, w);
        });
    }



    function sendData(x, y, speed) {
        if (socket.readyState === WebSocket.OPEN) {
            const newCoordinates = player.occupiedArea.slice(player.lastSentIndex + 1);

            player.lastSentIndex = player.occupiedArea.length - 1;
            const playerData = JSON.stringify({
                x: x,
                y: y,
                speed: speed,
                occupiedArea: newCoordinates,
            });
            socket.send(playerData);
        } else {
            console.warn("WebSocket not prepared. State:", socket.readyState);
        }
    }


    function checkCollision(player, trail) {
        for (let i = 0; i < trail.length - 1; i++) {
            if (player.x === trail[i].x && player.y === trail[i].y) {
                return true;
            }
        }
        return false;
    }

    function getFilledAreaCoordinates(trail) {
        if (!trail || trail.length === 0) return [];

        trail.sort((a, b) => a.y - b.y);

        const filledCoordinates = [];
        const minY = trail[0].y;
        const maxY = trail[trail.length - 1].y;

        for (let y = minY; y <= maxY; y++) {
            let intersections = [];
            for (let i = 0; i < trail.length; i++) {
                const current = trail[i];
                const next = trail[(i + 1) % trail.length];

                if (y >= Math.min(current.y, next.y) && y <= Math.max(current.y, next.y)) {

                    const x = current.x + (y - current.y) * (next.x - current.x) / (next.y - current.y);
                    intersections.push(x);
                }
            }
            intersections.sort((a, b) => a - b);
            for (let i = 0; i < intersections.length - 1; i += 2) {
                const startX = Math.ceil(intersections[i]);
                const endX = Math.floor(intersections[i + 1]);
                for (let x = startX; x <= endX; x++) {
                    filledCoordinates.push({ x, y });
                }
            }
        }

        return filledCoordinates;
    }

    function addCoordinates(occupiedArea, filledCoordinates) {
        const coordinatesSet = new Set(occupiedArea.map(p => `${p.x},${p.y}`));

        filledCoordinates.forEach(point => {
            const key = `${point.x},${point.y}`;
            if (!coordinatesSet.has(key)) {
                occupiedArea.push(point);
                coordinatesSet.add(key);
            }
        });
    }


    function updateGame() {

        if (started) {
            player.trail.push({ x: player.x, y: player.y });
        }

        if (checkCollision(player, player.occupiedArea)) {
            inCollision++;
        } else if (!checkCollision(player, player.occupiedArea) && inCollision >= 0) {
            inCollision--;
        }

        if (checkCollision(player, player.occupiedArea) && started && player.trail && inCollision === 1) {
            console.log('Zone closed!');
            addCoordinates(player.occupiedArea, getFilledAreaCoordinates(player.trail));

            ctx.clearRect(0, 0, canvas.width, canvas.height);
            drawOccupiedArea(ctx, player, 5, 5)
            player.trail = []
            const playerData = JSON.stringify({
                type: 'clearTrail',
            });
            socket.send(playerData);
        }
        if (checkCollision(player, player.trail) && started) {
            console.log('Self trail collision!');
        }
        if (checkCollision(player, enemy.trail)) {
            console.log('Enemies trail collision!');
        }

        const step = 3;

        if (player.direction === 'left' && player.x > 0) {
            player.x -= step * player.speed;
        } else if (player.direction === 'right' && player.x < canvas.width - player.width) {
            player.x += step * player.speed;
        } else if (player.direction === 'up' && player.y > 0) {
            player.y -= step * player.speed;
        } else if (player.direction === 'down' && player.y < canvas.height - player.height) {
            player.y += step * player.speed;
        }


        player.x = Math.max(0, Math.min(canvas.width - player.width, player.x));
        player.y = Math.max(0, Math.min(canvas.height - player.height, player.y));


    }

    function renderGame() {

        if (!started) {
            drawOccupiedArea(ctx, player)
            drawOccupiedArea(ctx, enemy)
        }

        if (initialized) {
            ctx.fillStyle = player.trailColor;
            player.trail.forEach(function (segment) {
                ctx.fillRect(segment.x + player.width / 4, segment.y + player.height / 4, player.width / 2, player.height / 2);
            });
        }


        ctx.fillStyle = player.color;
        ctx.fillRect(player.x, player.y, player.width, player.height);

        if (initialized) {
            ctx.fillStyle = enemy.trailColor;
            enemy.trail.forEach(function (segment) {
                ctx.fillRect(segment.x + enemy.width / 4, segment.y + enemy.height / 4, enemy.width / 2, enemy.height / 2);
            });
        }

        ctx.fillStyle = enemy.color;
        ctx.fillRect(enemy.x, enemy.y, enemy.width, enemy.height);
    }


    document.addEventListener('keydown', function (event) {
        started = true;
        if (play) {
            switch (event.key) {
                case 'ArrowLeft':
                case 'a':
                    player.direction !== 'right' ? player.direction = 'left' : null;
                    break;
                case 'ArrowRight':
                case 'd':
                    player.direction !== 'left' ? player.direction = 'right' : null;
                    break;
                case 'ArrowUp':
                case 'w':
                    player.direction !== 'down' ? player.direction = 'up' : null;
                    break;
                case 'ArrowDown':
                case 's':
                    player.direction !== 'up' ? player.direction = 'down' : null;
                    break;
            }
        }
    });

    function gameLoop() {
        if (!finished && player && play) {
            sendData(player.x, player.y, player.speed);
            updateGame();
            renderGame();
            animationFrameId = requestAnimationFrame(gameLoop);
        }
    }

    document.addEventListener('keydown', function (event) {
        if (event.code === 'Space' && play) {
            player.direction = null;
            started = false;
        }
    });

})