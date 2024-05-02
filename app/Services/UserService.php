<?php

namespace App\Services;

use Exception;
use App\Dao\UserDao;
use App\Models\User;
use App\Factories\UserFactory;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class UserService
{
    protected $userDao;
    protected $userFactory;

    public function __construct(UserDao $userDao, UserFactory $userFactory)
    {
        $this->userDao = $userDao;
        $this->userFactory = $userFactory;
    }

    public function getAll(int $paginate = 10): LengthAwarePaginator
    {
        try {
            return $this->userDao->getAll($paginate);
        } catch (Exception $ex) {
            Log::info($ex->getMessage());
        }

    }

    public function save(array $data): array
    {
        try {
            $newUser = $this->userFactory->make($data);
            $savedUser = $this->userDao->save($newUser);
    
            if(!is_null($savedUser))
            {
                return [
                    'message' => 'User saved',
                    'code' => Response::HTTP_CREATED    
                ];
            }
    
            return [
                'message' => 'Unable to save the data',
                'code' => Response::HTTP_UNPROCESSABLE_ENTITY            
            ];
        } catch (Exception $ex) {
            Log::info($ex->getMessage());
        }
    }

    public function update(array $data, int $id): array
    {
        try {
            $user = $this->userDao->findById($id);

            if(!$user)
            {
                return [
                    'message' => 'User not found',
                    'code' => Response::HTTP_UNPROCESSABLE_ENTITY    
                ];
            }
    
            $updatedUser = $this->userFactory->update(data: $data, user: $user);
            $savedUser = $this->userDao->save($updatedUser);
    
            if($savedUser)
            {
                return [
                    'message' => 'User updated',
                    'code' => Response::HTTP_CREATED    
                ];
            }
    
            return [
                'message' => 'Unable to save the data',
                'code' => Response::HTTP_INTERNAL_SERVER_ERROR            
            ];
        } catch (Exception $ex) {
            Log::info($ex->getMessage());
        }
    }

    public function getById(int $id): ?User
    {
        try {
            return $this->userDao->findById($id);
        } catch (Exception $ex) {
            Log::info($ex->getMessage());
        }
        
    }

    public function delete(int $id): array
    {
        try {
            $user = $this->userDao->findById($id);

            if(!$user)
            {
                return [
                    'message' => 'User not found',
                    'code' => Response::HTTP_UNPROCESSABLE_ENTITY    
                ];
            }
    
            $result = $this->userDao->delete($user);
    
            if($result)
            {
                return [
                    'message' => 'User deleted',
                    'code' => Response::HTTP_OK    
                ];
            }
    
            return [
                'message' => 'Unable to delete user',
                'code' => Response::HTTP_INTERNAL_SERVER_ERROR    
            ];
        } catch (Exception $ex) {
            Log::info($ex->getMessage());
        }
    }
}