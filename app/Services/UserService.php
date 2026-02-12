<?php 
namespace App\Services;


use App\Repositories\UserRepository;
use App\Models\User;
class UserService
{
    public function __construct(
        private UserRepository $users
    ) {}
 
    public function list()
    {
        return $this->users->all();
    }

    public function create(array $data): User
    {
        return $this->users->create($data);
    }

   public function update(User $user, array $data): User
{
    return $this->users->update($user->id, $data);
}


    public function delete(User $user): void
    {
        $this->users->delete($user->id);
    }

    public function attachProducts(User $user, array $productIds): void
    {
        $this->users->attachProducts($user, $productIds);
    }
}






?>