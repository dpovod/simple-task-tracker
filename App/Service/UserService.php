<?php
declare(strict_types=1);

namespace App\Service;

use App\Exception\Model\AttributeNotExistsException;
use App\Exception\Model\FieldTypeNotAllowedException;
use App\Exception\Validation\ValidationException;
use App\Model\User;
use App\Repository\Base\BaseRepository;
use App\Repository\UserRepository;
use App\Support\Validation\Rules\Email;
use App\Support\Validation\Rules\Required;
use App\Support\Validation\Rules\StringLength;
use App\Support\Validation\Validator;
use ReflectionException;

/**
 * Class UserService
 * @package App\Service
 */
class UserService
{
    /** @var User */
    private static $authUser;

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
        $validator = new Validator($user->getAttributes());

        $validator->setRules([
            'email' => [new Required(), new Email()],
            'login' => [new Required(), new StringLength(4, 10)],
            'password' => [new Required(), new StringLength(8, 20)],
        ]);

        $validator = $validator->setCallback(function ($validator) use ($user) {
            /** @var Validator $validator */
            /** @var User $user */
            /** @var User[] $existingUsers */
            $existingUsers = (new UserRepository())->findWhere(
                ['login' => $user->get('login'), 'email' => $user->get('email')],
                BaseRepository::CONDITION_OR
            );

            foreach ($existingUsers as $existingUser) {
                if ($existingUser->get('email') === $user->get('email')) {
                    $validator->addError('email', sprintf("User with email '%s' already exists.", $user->get('email')));
                }

                if ($existingUser->get('login') === $user->get('login')) {
                    $validator->addError('login', sprintf("User with login '%s' already exists.", $user->get('login')));
                }
            }
        });

        if (!$validator->isValid()) {
            throw new ValidationException($validator->getFirstError());
        }

        $password = $user->get('password');
        $password = password_hash($password, PASSWORD_BCRYPT);
        $user->set('password', $password);

        return (new UserRepository())->insert($user);
    }

    /**
     * @param string $login
     * @param string $password
     * @return bool
     * @throws AttributeNotExistsException
     * @throws ReflectionException
     * @throws ValidationException
     */
    public function login(string $login, string $password)
    {
        $repository = new UserRepository();
        $user = $repository->findFirstWhere(['login' => $login]);

        /** @var User $user */
        if ($user === null) {
            throw new ValidationException('Incorrect login or password.');
        }

        if (password_verify($password, $user->get('password'))) {
            session_start();
            $_SESSION['auth_id'] = $user->get('id');

            return true;
        }

        throw new ValidationException('Incorrect login or password.');
    }

    public function logout()
    {
        session_start();
        unset($_SESSION['auth_id']);
        session_write_close();
    }

    /**
     * @return int|null
     */
    public static function authUserId(): ?int
    {
        session_start();
        $id = isset($_SESSION['auth_id']) ? (int)$_SESSION['auth_id'] : null;
        session_write_close();

        return $id;
    }

    /**
     * @return User|null
     * @throws AttributeNotExistsException
     * @throws ReflectionException
     */
    public static function authUser(): User
    {
        if (isset(self::$authUser)) {
            return self::$authUser;
        }

        if ($id = self::authUserId()) {
            self::$authUser = (new UserRepository())->findFirstWhere(['id' => $id]);

            return self::$authUser;
        }

        return null;
    }
}
