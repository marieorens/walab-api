<?php

namespace App\Http\Controllers;

use App\Jobs\SendNewsletter;
use App\Models\Newletter;
use App\Models\NewsletterSubscriber;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Mail\NewsletterMail;
use Illuminate\Support\Facades\Mail;

class NewsletterSubscriberController extends Controller
{
     /**
     * Abonnement newslatter par User connecté.
     */
    public function subscribeUser()
    {
        $user = User::where('id', Auth::user())->first();
        $data = NewsletterSubscriber::updateOrCreate(
            ['user_id' => $user->id],
            ['email' => $user->email, 'subscribed_at' => now()]
        );
        return response()->json([
            'status' => 'success',
            'message' => 'Vous avez été abonné avec succès à notre newsletter !',
            'code' => 200,
            'date' => $data,
        ]); 
    }

    /**
     * Abonnement newslatter par Email non connecter.
     */
    public function subscribeEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'unique:newsletter_subscribers,email'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ]);
        }

        $data = NewsletterSubscriber::updateOrCreate(
            ['email' => $request->email],
            ['subscribed_at' => now()]
        );

        return response()->json([
            'status' => 'success',
            'message' => 'Vous avez été abonné avec succès à notre newsletter !',
            'code' => 200,
            'date' => $data,
        ]); 
    }

    /**
     * Désabonné User newslatter.
     */
    public function unsubscribe(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'exists:newsletter_subscribers,email'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ]);
        }
        $subscription = NewsletterSubscriber::where("email", $request->email)->first();

        if ($subscription) {
            $subscription->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'Vous avez été désabonné avec succès de notre newsletter.',
                'code' => 200,
            ]); 
        }
        return response()->json([
            'status' => 'success',
            'message' => 'Email non abonné',
            'code' => 400,
        ]); 
    }

    /**
     * Display a listing of the resource Newletter.
     */
    public function indexSubscribe()
    {
        $user_auth = User::where("id", Auth::user()->id)->first();
        $newsletterSubscribers = NewsletterSubscriber::paginate(5);
        return view('newletter.subscribe', compact('user_auth', 'newsletterSubscribers'));
    }

    /**
     * Display a listing of the resource Newletter.
     */
    public function index()
    {
        $user_auth = User::where("id", Auth::user()->id)->first();
        $newletters = Newletter::where('isdelete', false)->paginate(5);
        return view('newletter.index', compact('user_auth', 'newletters'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required'],
            'subject' => ['required'],
            'content' => ['required'],
            'type' => ['string'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ]);
        }
        try{
            $user_auth = User::where("id", Auth::user()->id)->first();
            $view = true;
            $newsletter = Newletter::create([
                'name' => isset($request->name) ? $request->name : null,
                'subject' => isset($request->subject) ? $request->subject : null,
                'html_file' => isset($request->html_file) ? $request->html_file : null,
                'content' => isset($request->content) ? $request->content : null,
                'type' => isset($request->type) ? $request->type : null,
            ]);
            //Publication
            SendNewsletter::dispatch($request->subject, $request->content);

            $message = "Le Newletter a été créé avec succès et publié.";
        }catch (\Exception $e){
            return back()->with('error', "Une erreur est survenue lors de la création du Newletter.");
        }
        return back()->with('success', $message);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {   
        try{
            $examen = Newletter::where("id", $id)->first();
            $examen->isdelete = true;
            $examen->save();
        }catch (\Exception $e){
            return back()->with('error', "Une erreur est survenue lors de la suppression du Newletter.");
        }
        return back()->with('success', "Le Newletter a été supprimé avec succès.");
    }
}
