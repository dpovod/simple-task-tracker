<?php
declare(strict_types=1);

namespace App\Service;

use App\Exception\Model\AttributeNotExistsException;
use App\Exception\Model\FieldTypeNotAllowedException;
use App\Exception\Validation\ValidationException;
use App\Model\User;
use App\Repository\Base\BaseRepository;
use App\Repository\UserRepository;
use App\Support\Validation\Validator;
use ReflectionException;
use RuntimeException;

/**
 * Class UserService
 * @package App\Service
 */
class UserService
{
    /**
     * @param User $user
     * @return bool
     * @throws AttributeNotExistsException
     * @throws FieldTypeNotAllowedException
     * @throws ReflectionException
     * @throws ValidationException
     */
    public function register(User $user)
    {
        $salt = getenv('APP_KEY');

        if (empty($salt)) {
            throw new RuntimeException('Salt is empty.', 500);
        }

        $password = $user->get('password');
        $password = password_hash($password, PASSWORD_BCRYPT, ['salt' => $salt]);
        $user->set('password', $password);
        $validator = new Validator();

        $validator = $validator->setCallback(function ($user) use ($validator) {
            /** @var User $user */
            /** @var User[] $existingUsers */
            $existingUsers = (new UserRepository())->findWhere(
                ['login' => $user->get('login'), 'email' => $user->get('email')],
                BaseRepository::CONDITION_OR
            );

            foreach ($existingUsers as $existingUser) {
                if ($existingUser->get('email') === $user->get('email')) {
                    $validator->addError('User with email already exists.');
                }

                if ($existingUser->get('login') === $user->get('login')) {
                    $validator->addError('User with login already exists.');
                }
            }
        });

        if (!$validator->isValid()) {
            throw new ValidationException($validator->getErrors()[0]);
        }

        return (new UserRepository())->insert($user);
    }
}
