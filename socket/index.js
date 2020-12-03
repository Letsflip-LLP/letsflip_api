require('dotenv').config()
var app = require('express')();
var http = require('http').Server(app);
var port = process.env.SOCKET_PORT || 3000;
var redis = require('redis')
var sub = redis.createClient()
const io            = require('socket.io')(http);
const socketioJwt   = require('socketio-jwt');
var Ioredis = require('ioredis');
var Redis = new Ioredis(); 
var sub = redis.createClient(process.env.REDIS_PORT,process.env.REDIS_HOST)
 
if(process.env.REDIS_PASSWORD){
  sub.auth(process.env.REDIS_PASSWORD)
}

io.sockets
.on('connection', socketioJwt.authorize({
  secret: process.env.JWT_SECRET,
  timeout: 15000 // 15 seconds to send the authentication message
})).on('authenticated', function(socket) {
  //this socket is authenticated, we are good to handle more events from it.
  const user = socket.decoded_token.data;
  io.emit('user',user); 
});  

Redis.subscribe('message', function (err, count) {
  // console.log('Total client : '+ count)
});

Redis.on('message', function (redis_channel, data) {
  console.log(data);
  sub.subscribe(redis_channel);
  data = JSON.parse(data)
  const socket_chanel = data.chanel;
  
  io.emit(socket_chanel,data);
  console.log('Redis message %s from channel %s' , JSON.stringify(data) , redis_channel);
  // console.log('Socket Emit message %s from channel %s', data, socket_chanel);
});

app.get('/', function(req, res){
  res.sendFile(__dirname + '/index.html');
});


if(process.env.SOCKET_IP){
  http.listen(port,process.env.SOCKET_IP,function(){
    console.log('listening on '+process.env.SOCKET_IP+':'+ port);
  });
}else{
  http.listen(port,function(){
    console.log('listening on *:' + port);
  });
}
