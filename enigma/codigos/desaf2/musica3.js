    function tentarTocar() {
      const audio = document.getElementById("musica");
      audio.play()
        .then(() => {
          console.log("Áudio reproduzido com sucesso");
          removerListeners();
        })
        .catch(err => {
          console.log("Interação ainda não aceita:", err);
        });
    }

    function removerListeners() {
      document.removeEventListener("keydown", tentarTocar);
      document.removeEventListener("click", tentarTocar);
      document.removeEventListener("scroll", tentarTocar);
      document.removeEventListener("mousemove", tentarTocar);
    }


    document.addEventListener("keydown", tentarTocar);
    document.addEventListener("click", tentarTocar);
    document.addEventListener("scroll", tentarTocar);
    document.addEventListener("mousemove", tentarTocar);