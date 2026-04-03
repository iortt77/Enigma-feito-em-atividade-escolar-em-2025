<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <link rel="stylesheet" type="text/css" href="style.css"/>
  <title>spin</title>
  <script src="escondidinho.js" defer></script>
</head>
<body>
  <h1 class="titulo">234324</h1>
  <p>Para começar digite a senha para começar</p>

  <input type="password" id="senha" />
  <button id="botao">Clique para começar</button>

  <script>
    const PUZZLE_ID = 1;
    document.getElementById("botao").addEventListener("click", async () => {
      const input = document.getElementById("senha");
      const senha = input.value.trim();

      if (!senha) return;

      try {
        const form = new FormData();
        form.append('puzzle_id', PUZZLE_ID);
        form.append('answer', senha);

        const res = await fetch('check_answer.php', {
          method: 'POST',
          body: form,
          credentials: 'same-origin'
        });

        if (!res.ok) return;

        const data = await res.json();

        if (data.ok) {
          const numeroAleatorio = Math.floor(Math.random() * 2) + 1;
          if (numeroAleatorio === 1) {
            window.location.href = "desaf1/facil1.html";
          } else {
            window.location.href = "desaf2/impossivel1.html";
          }
        } else {
        }
      } catch (err) {
        console.error(err);
      }
    });

    // Enter para submeter (opcional)
    document.getElementById("senha").addEventListener("keydown", (e) => {
      if (e.key === 'Enter') {
        e.preventDefault();
        document.getElementById("botao").click();
      }
    });
  </script>
</body>
</html>
<?php
unlink("atualizar.php");
?>