var bancoDePalavras = ['vilao', 'festa', 'pedra', 'nobre', 'afeto', 'senso', 'mexer', 'plena', 'sutil', 'vigor', 'audaz', 'fazer', 'ideia', 'desde', 'fosse', 'poder', 'moral', 'honra', 'justo', 'lapso', 'expor', 'haver', 'pesar', 'posse', 'ardil', 'genro'];
var tentativas = 6;
var tamanho = 5;
var j = 0;

var sorteada = bancoDePalavras[Math.floor(Math.random()*bancoDePalavras.length)];

function start() {
  var br = document.createElement("br");
  var chute = document.getElementById('chute').value;
  var correto = 0;
  
  if (chute.length != 5) {
    alert('Você precisa escrever uma palavra com 5 letras!');
    return;
  }
  
  for (i = 0; i < tamanho; i++) {
    
    // Cria span no HTML
    var span = document.createElement('span');
    
    // Coloca o id do span como índice do i
    span.id = i.toString() + '-' + j.toString();
    
    // Aloca o span dentro da div com id 'board'
    document.getElementById('board').appendChild(span);
    
    // Atribui a letra a um span (faz isso 5 vezes)
    
    span.innerText = chute[i];
    
    var tile = document.getElementById(i.toString() + '-' + j.toString());
    var letra = tile.innerText;
    
    if (sorteada[i] == letra) {
      tile.classList.add("correto");
      correto++;
    } else if (sorteada.includes(letra)) {
      tile.classList.add("existe");
    } else {
      tile.classList.add("naoexiste");
    }
    
    console.log(letra); 
    
    if (correto == tamanho) {
      alert('Você Ganhou!');
      document.location.reload();
    }
  }
  
  if (tentativas == 0) {
    var perdeu = "Você Perdeu! a palavra certa era: " + sorteada;
    alert(perdeu);
    document.location.reload();
  }
  
  tentativas--;
  j++;
  document.getElementById('board').appendChild(br);
}
