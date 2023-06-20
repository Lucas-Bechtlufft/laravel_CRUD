<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Event;
use App\Models\User;

class EventController extends Controller
{
    
    public function index() {

        $search = request('search');

        if($search) {

            $events = Event::where([
                ['title', 'like', '%'.$search.'%']
            ])->get();

        } else {
            $events = Event::all();
        }        
    
        return view('welcome',['events' => $events, 'search' => $search]);

    }

    public function create() {
        return view('events.create');
    }

    public function store(Request $request) {

        $event = new Event;

        $event->title = $request->title;
        $event->date = $request->date;
        $event->city = $request->city;
        $event->private = $request->private;
        $event->description = $request->description;
        $event->items = $request->items;

        // Image Upload
        if($request->hasFile('image') && $request->file('image')->isValid()) {

            $requestImage = $request->image;

            $extension = $requestImage->extension();

            $imageName = md5($requestImage->getClientOriginalName() . strtotime("now")) . "." . $extension;

            $requestImage->move(public_path('img/events'), $imageName);

            $event->image = $imageName;

        }

        $user = auth()->user();
        $event->user_id = $user->id;

        $event->save();

        return redirect('/')->with('msg', 'Evento criado com sucesso!');

    }

    public function show($id) {

        $event = Event::findOrFail($id);

        $user = auth()->user();
        $hasUserJoined = false;

        if($user) {

            $userEvents = $user->eventsAsParticipant->toArray();

            foreach($userEvents as $userEvent) {
                if($userEvent['id'] == $id) {
                    $hasUserJoined = true;
                }
            }

        }

        $eventOwner = User::where('id', $event->user_id)->first()->toArray();

        return view('events.show', ['event' => $event, 'eventOwner' => $eventOwner, 'hasUserJoined' => $hasUserJoined]);
        
    }

    public function dashboard()
    {
        $user = auth()->user();
    
        // Verificar se o usuário autenticado é um administrador
        if ($user->isAdmin()) {
            $events = Event::all();
            $eventsAsParticipant = Event::whereIn('id', $user->eventsAsParticipant->pluck('id'))->get();
        } else {
            $events = $user->events;
            $eventsAsParticipant = $user->eventsAsParticipant;
        }
    
        return view('events.dashboard', [
            'events' => $events,
            'eventsasparticipant' => $eventsAsParticipant
        ]);
    }
    

    public function destroy($id)
    {
        $event = Event::findOrFail($id);
        $user = auth()->user();
    
        // Verificar se o usuário autenticado é um administrador ou o criador do evento
        if ($user->isAdmin() || $user->id == $event->user_id) {
            // Remover os registros relacionados na tabela event_user
            $event->users()->detach();
    
            // Excluir o evento
            $event->delete();
    
            return redirect('/dashboard')->with('msg', 'Evento excluído com sucesso!');
        } else {
            // Redirecionar ou retornar uma resposta de acesso negado para usuários não autorizados
            return redirect('/dashboard')->with('error', 'Acesso negado! Você não tem permissão para excluir este evento.');
        }
    }
    
    

    public function edit($id)
    {
        $user = auth()->user();
        $event = Event::findOrFail($id);
    
        // Verificar se o usuário autenticado é um administrador ou o criador do evento
        if ($user->isAdmin() || $user->id == $event->user_id) {
            return view('events.edit', ['event' => $event]);
        } else {
            // Redirecionar ou retornar uma resposta de acesso negado para usuários não autorizados
            return redirect('/dashboard')->with('error', 'Acesso negado! Você não tem permissão para editar este evento.');
        }
    }

    public function update(Request $request) {

        $data = $request->all();

        // Image Upload
        if($request->hasFile('image') && $request->file('image')->isValid()) {

            $requestImage = $request->image;

            $extension = $requestImage->extension();

            $imageName = md5($requestImage->getClientOriginalName() . strtotime("now")) . "." . $extension;

            $requestImage->move(public_path('img/events'), $imageName);

            $data['image'] = $imageName;

        }

        Event::findOrFail($request->id)->update($data);

        return redirect('/dashboard')->with('msg', 'Evento editado com sucesso!');

    }

    public function joinEvent($id) {

        $user = auth()->user();

        $user->eventsAsParticipant()->attach($id);

        $event = Event::findOrFail($id);

        return redirect('/dashboard')->with('msg', 'Sua presença está confirmada no evento ' . $event->title);

    }

    public function leaveEvent($id) {

        $user = auth()->user();

        $user->eventsAsParticipant()->detach($id);

        $event = Event::findOrFail($id);

        return redirect('/dashboard')->with('msg', 'Você saiu com sucesso do evento: ' . $event->title);

    }

}