<?php

namespace App\Livewire;

use App\Livewire\Traits\Alert;
use App\Models\Event;
use App\Models\Participant;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;
use Livewire\Attributes\Validate;
use Illuminate\Support\Arr;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Symfony\Component\Finder\SplFileInfo;

class EventDetailPage extends Component
{
    use Alert, WithFileUploads;

    // public Event $event; // Jangan dipake, crash ini.
    public $id;

    public $team;
    public $name_emergency;
    public $relation_emergency;
    public $phone_emergency;

    public $sehat_jasmani_rohani;
    public $sehat_jantung;
    public $tanpa_epilepsi;
    public $sehat_pernafasan;
    public $tidak_dalam_perawatan;
    public $mampu_berenang;
    public $surat_sehat;

    public $info_dipertanggungjawabkan;
    public $sehat_dan_siap;
    public $bebas_tuntutan;
    
    public $metode_bayar = 'transfer';
    public $nominal_bayar = 0;
    public $catatan;
    
    #[Validate('image|max:2048|mimes:png,jpg,jpeg|nullable')]
    public $image ;

   public function deleteUpload(array $content): void
    {
        /*
        the $content contains:
        [
            'temporary_name',
            'real_name',
            'extension',
            'size',
            'path',
            'url',
        ]
        */
    
        if (! $this->image) {
            return;
        }
    
        $files = Arr::wrap($this->image);
    
        /** @var UploadedFile $file */
        $file = collect($files)->filter(fn (UploadedFile $item) => $item->getFilename() === $content['temporary_name'])->first();
    
        // 1. Here we delete the file. Even if we have a error here, we simply
        // ignore it because as long as the file is not persisted, it is
        // temporary and will be deleted at some point if there is a failure here.
        rescue(fn () => $file->delete(), report: false);
    
        $collect = collect($files)->filter(fn (UploadedFile $item) => $item->getFilename() !== $content['temporary_name']);
    
        // 2. We guarantee restore of remaining files regardless of upload
        // type, whether you are dealing with multiple or single uploads
        $this->image = is_array($this->image) ? $collect->toArray() : $collect->first();
    }

    public function mount($slug)
    {
        if (Auth::check()) {
            if (Auth::user()->street == null || Auth::user()->village == null || Auth::user()->district == null || Auth::user()->city == null || Auth::user()->state == null) {
                return redirect()->intended(route('user.profilealamat', absolute: false));
            } elseif (Auth::user()->image == null || Auth::user()->gender == null || Auth::user()->tempat_lahir == null || Auth::user()->tanggal_lahir == null || Auth::user()->image_id == null || Auth::user()->no_id == null || Auth::user()->ukuran_jersey == null) {
                return redirect()->intended(route('user.profiledatadiri', absolute: false));
            } else {
                $slug;
            }
        }
        $this->id = Event::all()->where('slug', 'likes', $slug)->value('id');
    }

    #[Title('Event Detail')]
    public function render()
    {
        $daftar_klub = User::query()
            ->orderBy('klub')->whereNotNull('klub')->whereNot('klub','')->get()
            ->map(fn(User $daftar_klub): array => [
                'label' => $daftar_klub->klub. " ~ ketua : $daftar_klub->name",
                'value' => $daftar_klub->id,
            ]);

        $partisipan = Participant::all();

        return view('livewire.event-detail-page', [
            'event' => Event::find($this->id),
            'daftar_klub' => $daftar_klub,
            'partisipan' => $partisipan,

        ])
            // ->title(Event::find($this->id)->title) // custom title
        ;
    }


    public function ubah_team(): void {
        Participant::where('user_id', Auth::user()->id)->where('participantable_type', Event::class)->where('participantable_id',$this->id)->orderBy('id')->get()->first()->update([
                'team' => $this->team,
            ]);
        $this->success();
    }

    public function bayar_event(): void {
        if (Auth::check()) {
            $this->validate([
                'nominal_bayar' => [
                    'required',
                    'string',
                    'min:0',
                ],
            ]);
        }

        if (Payment::where('user_id', Auth::user()->id)->where('paymentable_type', Event::class)->where('paymentable_id',$this->id)->orderBy('id')->get()->first()->nominal == 0) {            
            if ($this->image != null) {
                    Storage::disk('public')->putFile('payments', $this->image);
                    $image = Storage::disk('public')->putFile('payments', $this->image);
                    $updateimage = $image;
                } else {
                    $updateimage = $this->image;
            }
            Payment::where('user_id', Auth::user()->id)->where('paymentable_type', Event::class)->where('paymentable_id',$this->id)->orderBy('id')->get()->first()->update([
                'image' => $updateimage,
                'payment_method' => $this->metode_bayar,
                'nominal_plus' => intval(Str::replace('.', '', $this->nominal_bayar)),
                'nominal' => intval(Str::replace('.', '', $this->nominal_bayar)),
                'notes' => $this->catatan,
            ]);
        } else {
            $pembayaran = new Payment();

                if ($this->image != null) {
                    Storage::disk('public')->putFile('payments', $this->image);
                    $image = Storage::disk('public')->putFile('payments', $this->image);
                    $pembayaran->image = $image;
                } else {
                    $pembayaran->image = $this->image;
                }

            $pembayaran->paymentable_id = $this->id;
            $pembayaran->paymentable_type = Event::class;
            $pembayaran->mutation_type = 'Event';
            $pembayaran->date_payment = date('Y-m-d H:i:s');
            $pembayaran->payment_method = $this->metode_bayar;
            $pembayaran->notes = $this->catatan;
            $pembayaran->currency = 'idr';
            $pembayaran->nominal_plus = intval(Str::replace('.', '', $this->nominal_bayar));
            $pembayaran->nominal_mins = 0;
            $pembayaran->nominal = intval(Str::replace('.', '', $this->nominal_bayar));
            $pembayaran->user_id = Auth::user()->id;
            $pembayaran->created_by = Auth::user()->id;
            $pembayaran->updated_by = Auth::user()->id;
            $pembayaran->save();
        }

        $this->image = null;
        $this->metode_bayar = 'transfer';
        $this->nominal_bayar = 0;
        $this->catatan = '';

        $this->success();
    }

    public function daftar_event(): void
    {

        if (Auth::check()) {

            $this->validate([
                'name_emergency' => [
                    'required',
                    'string',
                    'max:255',
                ],
                'relation_emergency' => [
                    'required',
                    'string',
                    'max:255',
                ],
                'phone_emergency' => [
                    'required',
                    'string',
                    'max:255',
                ],
                'sehat_jasmani_rohani' => [
                    'required',
                ],
                'sehat_jantung' => [
                    'required',
                ],
                'tanpa_epilepsi' => [
                    'required',
                ],
                'sehat_pernafasan' => [
                    'required',
                ],
                'tidak_dalam_perawatan' => [
                    'required',
                ],
                'mampu_berenang' => [
                    'required',
                ],
                'surat_sehat' => [
                    'required',
                ],
                'info_dipertanggungjawabkan' => [
                    'required',
                ],
                'sehat_dan_siap' => [
                    'required',
                ],
                'bebas_tuntutan' => [
                    'required',
                ],
                'nominal_bayar' => [
                    'required',
                    'string',
                    'min:0',
                ],
            ]);

            $sehat_jasmani_rohani = ($this->sehat_jasmani_rohani == true) ? "sehat_jasmani_rohani" : null;
            $sehat_jantung = ($this->sehat_jantung == true) ? "sehat_jantung" : null;
            $tanpa_epilepsi = ($this->tanpa_epilepsi == true) ? "tanpa_epilepsi" : null;
            $sehat_pernafasan = ($this->sehat_pernafasan == true) ? "sehat_pernafasan" : null;
            $tidak_dalam_perawatan = ($this->tidak_dalam_perawatan == true) ? "tidak_dalam_perawatan" : null;
            $mampu_berenang = ($this->mampu_berenang == true) ? "mampu_berenang" : null;
            $surat_sehat = ($this->surat_sehat == true) ? "surat_sehat" : null;

            $info_dipertanggungjawabkan = ($this->info_dipertanggungjawabkan == true) ? "info_dipertanggungjawabkan" : null;
            $sehat_dan_siap = ($this->sehat_dan_siap == true) ? "sehat_dan_siap" : null;
            $bebas_tuntutan = ($this->bebas_tuntutan == true) ? "bebas_tuntutan" : null;

            $peserta = new Participant();
            $peserta->participantable_id = $this->id;
            $peserta->participantable_type = Event::class;
            $peserta->team = $this->team;
            $peserta->name_emergency = $this->name_emergency;
            $peserta->relation_emergency = $this->relation_emergency;
            $peserta->phone_emergency = $this->phone_emergency;
            $peserta->health_verification = array($sehat_jasmani_rohani, $sehat_jantung, $tanpa_epilepsi, $sehat_pernafasan, $tidak_dalam_perawatan, $mampu_berenang, $surat_sehat);
            $peserta->statement_of_agreement = array($info_dipertanggungjawabkan, $sehat_dan_siap, $bebas_tuntutan);
            $peserta->user_id = Auth::user()->id;
            $peserta->created_by = Auth::user()->id;
            $peserta->updated_by = Auth::user()->id;
            $peserta->save();


            $pembayaran = new Payment();

            if ($this->image != null) {
                Storage::disk('public')->putFile('payments', $this->image);
                $image = Storage::disk('public')->putFile('payments', $this->image);
                $pembayaran->image = $image;
            } else {
                $pembayaran->image = $this->image;
            }


            $pembayaran->paymentable_id = $this->id;
            $pembayaran->paymentable_type = Event::class;
            $pembayaran->mutation_type = 'Event';
            $pembayaran->date_payment = date('Y-m-d H:i:s');
            $pembayaran->payment_method = $this->metode_bayar;
            $pembayaran->notes = $this->catatan;
            $pembayaran->currency = 'idr';
            $pembayaran->nominal_plus = intval(Str::replace('.', '', $this->nominal_bayar));
            $pembayaran->nominal_mins = 0;
            $pembayaran->nominal = intval(Str::replace('.', '', $this->nominal_bayar));
            $pembayaran->user_id = Auth::user()->id;
            $pembayaran->created_by = Auth::user()->id;
            $pembayaran->updated_by = Auth::user()->id;
            $pembayaran->save();

            $this->name_emergency = '';
            $this->relation_emergency = '';
            $this->phone_emergency = '';

            $this->sehat_jasmani_rohani = null;
            $this->sehat_jantung = null;
            $this->tanpa_epilepsi = null;
            $this->sehat_pernafasan = null;
            $this->tidak_dalam_perawatan = null;
            $this->mampu_berenang = null;
            $this->surat_sehat = null;

            $this->info_dipertanggungjawabkan = null;
            $this->sehat_dan_siap = null;
            $this->bebas_tuntutan = null;


            $this->image = null;
            $this->metode_bayar = 'transfer';
            $this->nominal_bayar = 0;
            $this->catatan = '';

            $this->success();
        } else {
            redirect('/login');
        }
    }

    
}
