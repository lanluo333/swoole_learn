<?php


// use Swoole\WebSocket\Server;

class WebSocket
{
    private $server;

    private $table;

    // protected $config;

    const HOST = '0.0.0.0';
    const PORT = 9501;

    protected $name = ['刘备','诸葛亮','关羽','张飞','马超','曹操','赵云','黄忠',
        '吕布','貂蝉','孙权','孙策','袁绍','董卓'];

    protected $avatar = [
        './images/avatar/1.jpg',
        './images/avatar/2.jpg',
        './images/avatar/3.jpg',
        './images/avatar/4.jpg',
        './images/avatar/5.jpg',
        './images/avatar/6.jpg',
        './images/avatar/7.jpg',
    ];

    public function __construct()
    {
        $this->createTable();
        // $this->config = Config::instance();
    }

    /**
     * 启动
     */
    public function run()
    {
        $this->server = new swoole_websocket_server(self::HOST, self::PORT);

        $this->server->on('open', [$this, 'open']);
        $this->server->on('message', [$this, 'message']);
        $this->server->on('close', [$this, 'close']);

        $this->server->start();
    }

    /**
     * @param Server $server
     * @param $request
     */
    public function open($server, $request)
    {
        $user = [
            'fd' => $request->fd,
            // 'name' => $this->config['webim']['name'][array_rand($this->config['webim']['name'])].$request->fd,
            'name' => $this->name[rand(0,count($this->name)-1)].$request->fd,
            // 'avatar' => $this->config['webim']['avatar'][array_rand($this->config['webim']['avatar'])]
            'avatar' => $this->avatar[rand(0,count($this->avatar)-1)]
        ];
        $this->table->set($request->fd, $user);

        $server->push($request->fd, json_encode(
                array_merge(['user' => $user], ['all' => $this->allUser()], ['type' => 'openSuccess'])
            )
        );
        $this->pushMessage($server, "欢迎".$user['name']."进入聊天室", 'open', $request->fd);
    }

    private function allUser()
    {
        $users = [];
        foreach ($this->table as $row) {
            $users[] = $row;
        }
        return $users;
    }

    /**
     * @param Server $server
     * @param $frame
     */
    public function message($server, $frame)
    {
        $this->pushMessage($server, $frame->data, 'message', $frame->fd);
    }


    /**
     * @param Server $server
     * @param $fd
     */
    public function close($server, $fd)
    {
        $user = $this->table->get($fd);
        $this->pushMessage($server, $user['name']."离开聊天室", 'close', $fd);
        $this->table->del($fd);
    }

    /**
     * 遍历发送消息
     *
     * @param Server $server
     * @param $message
     * @param $messageType
     * @param int $skip
     */
    private function pushMessage($server, $message, $messageType, $frameFd)
    {
        $message = htmlspecialchars($message);
        $datetime = date('Y-m-d H:i:s', time());
        $user = $this->table->get($frameFd);
        foreach ($this->table as $row) {
            if ($frameFd == $row['fd']) {
                continue;
            }
            $server->push($row['fd'], json_encode([
                    'type' => $messageType,
                    'message' => $message,
                    'datetime' => $datetime,
                    'user' => $user
                ])
            );
        }
    }

    /**
     * 创建内存表
     */
    private function createTable()
    {
        $this->table = new \swoole_table(1024);
        $this->table->column('fd', \swoole_table::TYPE_INT);
        $this->table->column('name', \swoole_table::TYPE_STRING, 255);
        $this->table->column('avatar', \swoole_table::TYPE_STRING, 255);
        $this->table->create();
    }
}


(new WebSocket())->run();

