<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Person;
use App\Models\Parentage;
use App\Models\Marriage;
use App\Models\PersonClosure;
use Illuminate\Support\Facades\DB;

class PersonSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            $gg1 = Person::create(['first_name'=>'Miguel','last_name'=>'Garcia']);
            $gg2 = Person::create(['first_name'=>'Luisa','last_name'=>'Diaz']);
            $gg3 = Person::create(['first_name'=>'Alberto','last_name'=>'Lopez']);
            $gg4 = Person::create(['first_name'=>'Carmen','last_name'=>'Ruiz']);
            $gg5 = Person::create(['first_name'=>'Ricardo','last_name'=>'Perez']);
            $gg6 = Person::create(['first_name'=>'Elvira','last_name'=>'Soto']);
            $gg7 = Person::create(['first_name'=>'Hector','last_name'=>'Torres']);
            $gg8 = Person::create(['first_name'=>'Rosa','last_name'=>'Vega']);

            $gp1 = Person::create(['first_name'=>'Juan','last_name'=>'Garcia']);
            $gp2 = Person::create(['first_name'=>'Maria','last_name'=>'Lopez']);
            $gp3 = Person::create(['first_name'=>'Pedro','last_name'=>'Perez']);
            $gp4 = Person::create(['first_name'=>'Ana','last_name'=>'Torres']);

            $p1 = Person::create(['first_name'=>'Carlos','last_name'=>'Garcia']);
            $p2 = Person::create(['first_name'=>'Lucia','last_name'=>'Perez']);

            $c1 = Person::create(['first_name'=>'Sofia','last_name'=>'Garcia']);
            $c2 = Person::create(['first_name'=>'Diego','last_name'=>'Garcia']);
            $c3 = Person::create(['first_name'=>'Elena','last_name'=>'Garcia']);

            $s1 = Person::create(['first_name'=>'Marco','last_name'=>'Ramos']);
            $gc1 = Person::create(['first_name'=>'Valeria','last_name'=>'Ramos']);
            $gc1sp = Person::create(['first_name'=>'Nicolas','last_name'=>'Ramos']);
            $ggc1 = Person::create(['first_name'=>'Lucas','last_name'=>'Ramos']);

            foreach (Person::all() as $person) {
                PersonClosure::firstOrCreate(['ancestor_id'=>$person->id,'descendant_id'=>$person->id], ['depth'=>0]);
            }

            Marriage::create(['spouse_a_id'=>$gg1->id,'spouse_b_id'=>$gg2->id,'start_date'=>'1945-01-01','status'=>'active']);
            Marriage::create(['spouse_a_id'=>$gg3->id,'spouse_b_id'=>$gg4->id,'start_date'=>'1947-01-01','status'=>'active']);
            Marriage::create(['spouse_a_id'=>$gg5->id,'spouse_b_id'=>$gg6->id,'start_date'=>'1949-01-01','status'=>'active']);
            Marriage::create(['spouse_a_id'=>$gg7->id,'spouse_b_id'=>$gg8->id,'start_date'=>'1951-01-01','status'=>'active']);
            Marriage::create(['spouse_a_id'=>$gp1->id,'spouse_b_id'=>$gp2->id,'start_date'=>'1970-01-01','status'=>'active']);
            Marriage::create(['spouse_a_id'=>$gp3->id,'spouse_b_id'=>$gp4->id,'start_date'=>'1972-01-01','status'=>'active']);
            Marriage::create(['spouse_a_id'=>$p1->id,'spouse_b_id'=>$p2->id,'start_date'=>'1995-01-01','status'=>'active']);
            Marriage::create(['spouse_a_id'=>$c1->id,'spouse_b_id'=>$s1->id,'start_date'=>'2020-01-01','status'=>'active']);
            Marriage::create(['spouse_a_id'=>$gc1->id,'spouse_b_id'=>$gc1sp->id,'start_date'=>'2023-01-01','status'=>'active']);

            $this->attachParentage($gg1->id, $gp1->id, 'father');
            $this->attachParentage($gg2->id, $gp1->id, 'mother');
            $this->attachParentage($gg3->id, $gp2->id, 'father');
            $this->attachParentage($gg4->id, $gp2->id, 'mother');
            $this->attachParentage($gg5->id, $gp3->id, 'father');
            $this->attachParentage($gg6->id, $gp3->id, 'mother');
            $this->attachParentage($gg7->id, $gp4->id, 'father');
            $this->attachParentage($gg8->id, $gp4->id, 'mother');

            $this->attachParentage($gp1->id, $p1->id, 'father');
            $this->attachParentage($gp2->id, $p1->id, 'mother');
            $this->attachParentage($gp3->id, $p2->id, 'father');
            $this->attachParentage($gp4->id, $p2->id, 'mother');

            $this->attachParentage($p1->id, $c1->id, 'father');
            $this->attachParentage($p2->id, $c1->id, 'mother');
            $this->attachParentage($p1->id, $c2->id, 'father');
            $this->attachParentage($p2->id, $c2->id, 'mother');
            $this->attachParentage($p1->id, $c3->id, 'father');
            $this->attachParentage($p2->id, $c3->id, 'mother');

            $this->attachParentage($c1->id, $gc1->id, 'mother');
            $this->attachParentage($s1->id, $gc1->id, 'father');
            $this->attachParentage($gc1->id, $ggc1->id, 'mother');
            $this->attachParentage($gc1sp->id, $ggc1->id, 'father');
        });
    }

    private function attachParentage(int $parentId, int $childId, string $type): void
    {
        Parentage::firstOrCreate(['parent_id'=>$parentId,'child_id'=>$childId,'type'=>$type]);
        $ancestors = PersonClosure::where('descendant_id', $parentId)->get(['ancestor_id','depth']);
        $descendants = PersonClosure::where('ancestor_id', $childId)->get(['descendant_id','depth']);
        foreach ($ancestors as $a) {
            foreach ($descendants as $d) {
                $depth = ($a->depth + 1 + $d->depth);
                PersonClosure::updateOrCreate(['ancestor_id'=>$a->ancestor_id,'descendant_id'=>$d->descendant_id], ['depth'=>$depth]);
            }
        }
    }
}
