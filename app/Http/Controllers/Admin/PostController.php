<?php

namespace App\Http\Controllers\Admin;

use App\Post;
use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PostController extends Controller
{
    private $post;

    public function __construct(Post $post) {
        $this->post = $post;
    }
    // index
    public function index() {
        /*
        // realizando uma busca por todos os registros e paginando o resultado
        $posts = Post::paginate(10);
        // realizando uma busca para uma única postagem específica
        // $posts = Post::find(1);
        // realizando uma busca e tratando se o registro existe ou não (excpetion)
        // $posts = Post::findOrFail(100);
        // dd($posts);
        return view('posts.index', compact('posts'));
        */
        $posts = $this->post->paginate(15);
        return view('posts.index', compact('posts'));
    }

    // create
    public function create() {
        // indicando qual view será retornada
        // return view('posts.create');
        $categories = \App\Category::all(['id','name']);
    }

    // store
    public function store(Request $request) {
        /*
        ## exibindo todos os valores que estão sendo enviados após o POST do formulário
        ## dd($request->all());
        ## exibindo um valor específico
        ## dd($request->get('title'));
        ## ou
        ## dd($request->title);
        ## ou
        ## dd($request->input('title'));

        ## verificando se existe um determinado campo na requisição
        /**
        *if ($request->has('title')) {
        *    dd($request->title);
        *}

        ## ou
        ## if ($request->hasAny(['title','content','slug'])) {
        ##    dd($request->title);
        ## }

        // populando a tabela do banco por meio do Eloquent
        // criando uma variável para receber todos os dados da requisição
        $data = $request->all();
        // realizando uma inserção (ou atualização) em massa
        // $data['user_id'] = 1;
        $data['is_active'] = false;
        $user = User::find(1);
        // dd(Post::create($data));
        dd($user->posts()->create($data));
        /*
        // criando um novo objeto ($post) da classe Post (apenas para inserir um novo registro)
        $post = new Post();
        // alterando um registro da tabela
        // ao invés de criar um objeto e instânciá-lo como um novo objeto da classe Post
        // nós criamos o objeto com os atributos do registro informado no id (find(1))
        // $post = Post::find(1);
        // definindo os valores dos atributos do objeto $post
        $post->title        =       $data['title'];
        $post->description  =       $data['description'];
        $post->content      =       $data['content'];
        $post->slug         =       $data['slug'];
        $post->is_active    =       true;
        $post->user_id      =       1;
        // testando o processo de salvamento no banco
        dd($post->save());
        */
        $data = $request->all();
        try {
            $data['is_active'] = true;
            //$user = User::find(1);

            $user = auth()->user();

            $post = $user->posts()->create($data);

            $post->categories()->sync($data['categories']);
            flash('Postagem inserida com sucesso!')->success();
            return redirect()->route('posts.index');
        }
        catch (Exception $e) {
            $message = 'Erro ao inserir categoria!';
            if (env('APP_DEBUG')) {
                $message = $e->getMessage();
            }
            flash($message)->warning();
            return redirect()->back();
        }
    }

    // show
    public function show(Post $post) {
        /*
        $post = Post::findOrFail($id);
        //dd($post);
        */
        $categories = \App\Category::all(['id','name']);
        return view('posts.edit',compact('post','categories'));
    }

    // update
    public function update(Post $post, Request $request) {
        // atualizando por meio do 'mass assignment'
        $data = $request->all();
        /*
        $post = Post::findOrFail($id);
        dd($post->update($data));
        */
        try {
            $post->update($data);

            $post->categories()->sync($data['categories']);
            flash('Postagem atualizada com sucesso!')->success();
            return redirect()->route('posts.show', ['post' => $post->id]);
        }
        catch(Exception $e) {
            $message = 'Erro ao atualizar categoria!';
            if (env('APP_DEBUG')) {
                $message = $e->getMessage();
            }
            flash($message)->warning();
            return redirect()->back();
        }
    }

    // destroy
    public function destroy(Post $post) {
        /*
        $post = Post::findOrFail($id);
        dd($post->delete());*/
        try {
            $post->delete($post);
            flash('Postagem removida com sucesso')->success();
            return redirect()->route('posts.index');
        }
        catch (Excpetion $e) {
            $message = 'Erro ao remover postagem!';
            if (env('APP_DEBUG')) {
                $message = $e->getMessage();
            }
            flash($message)->warning();
            return redirect()->back();
        }

    }


}
